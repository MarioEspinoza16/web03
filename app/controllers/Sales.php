<?php
require_once __DIR__ . '/../helpers/lab_helper.php';
class Sales extends Controller
{
    private $saleModel;
    private $productModel;
    public function __construct()
    {
        if (($_SESSION['autenticado'] ?? '') !== 'SI') labRedirect('/users/login');
        try { $this->saleModel = $this->model('Sale'); $this->productModel = $this->model('Product'); }
        catch (RuntimeException $e) { http_response_code(500); exit(labEscape($e->getMessage())); }
    }
    public function index()
    {
        $this->view('sales/index', ['title'=>'Venta de productos', 'receipts'=>$_SESSION['lab_sales'] ?? []]);
    }
    public function assisted() { $this->checkout('assisted'); }
    public function selfservice() { $this->checkout('selfservice'); }
    private function checkout($mode)
    {
        $data = ['title'=>$mode === 'assisted' ? 'Venta asistida por empleado' : 'Compra autogestionada', 'mode'=>$mode,
                 'CustomerID'=>'','EmployeeID'=>'','ShipVia'=>'','ShippedDate'=>date('Y-m-d'),'RequiredDate'=>date('Y-m-d',strtotime('+7 days')),'Discount'=>'0', 'quantities'=>[], 'errors'=>[]];
        $data['customers'] = $this->saleModel->getCustomers();
        $data['employees'] = $this->saleModel->getEmployees();
        $data['shippers'] = $this->saleModel->getShippers();
        $data['categories'] = $this->productModel->getCategories();
        $data['products'] = array_values(array_filter($this->productModel->getProducts(), fn($p)=>!$p->Discontinued));
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            labCheckPost();
            foreach (['CustomerID','EmployeeID','ShipVia','RequiredDate','ShippedDate','Discount'] as $field) $data[$field] = labPost($field);
            if ($mode === 'selfservice') { $data['EmployeeID']=''; $data['Discount']='0'; }
            foreach (['CustomerID'=>'customers','ShipVia'=>'shippers'] as $field=>$list) {
                $key = $field === 'ShipVia' ? 'ShipperID' : $field;
                if (!in_array($data[$field], array_map(fn($r)=>(string) $r->$key,$data[$list]),true)) $data['errors'][] = 'Seleccione un cliente y un transportista existentes.';
            }
            if ($mode === 'assisted' && !in_array($data['EmployeeID'],array_map(fn($r)=>(string) $r->EmployeeID,$data['employees']),true)) $data['errors'][] = 'Seleccione un empleado existente.';
            $date = DateTime::createFromFormat('!Y-m-d', $data['RequiredDate']);
            if (!$date || $date->format('Y-m-d') !== $data['RequiredDate'] || $data['RequiredDate'] < date('Y-m-d')) $data['errors'][] = 'La fecha requerida debe ser hoy o una fecha posterior.';
            $shipping = DateTime::createFromFormat('!Y-m-d', $data['ShippedDate']);
            if (!$shipping || $shipping->format('Y-m-d') !== $data['ShippedDate'] || $data['ShippedDate'] < date('Y-m-d')) $data['errors'][] = 'Seleccione una fecha de envío válida, hoy o posterior.';
            if ($data['ShippedDate'] > $data['RequiredDate']) $data['errors'][] = 'La fecha de envío no puede ser posterior a la fecha requerida.';
            if (!preg_match('/^\d{1,3}$/D',$data['Discount']) || (int)$data['Discount'] > 100) $data['errors'][] = 'El descuento debe ser un porcentaje entero entre 0 y 100.';
            $items = [];
            $raw = $_POST['quantity'] ?? [];
            if (!is_array($raw) || count($raw)>1000) { $data['errors'][] = 'Selección de productos inválida.'; $raw=[]; }
            foreach ($raw as $id=>$value) {
                if (!is_scalar($value)) { $data['errors'][]='Cantidad inválida.'; continue; }
                $data['quantities'][$id] = (string)$value;
                $quantity = filter_var($value,FILTER_VALIDATE_INT,['options'=>['min_range'=>0,'max_range'=>32767]]);
                $productId = filter_var($id,FILTER_VALIDATE_INT,['options'=>['min_range'=>1,'max_range'=>2147483647]]);
                if ($quantity === false || $productId === false) $data['errors'][] = 'Las cantidades deben ser enteros entre 0 y 32767.';
                elseif ($quantity > 0) $items[$productId] = $quantity;
            }
            if (!$items) $data['errors'][] = 'Seleccione al menos un producto con cantidad mayor que cero.';
            if (!$data['errors']) {
                // Nonce de un solo uso: evita registrar dos veces por doble clic o reenvío.
                $nonce = labPost('sale_nonce');
                if (!$nonce || !isset($_SESSION['sale_nonces'][$nonce]) || $_SESSION['sale_nonces'][$nonce] !== $mode) $data['errors'][] = 'Este formulario ya fue enviado o venció. Revise los datos e intente nuevamente.';
                else {
                    unset($_SESSION['sale_nonces'][$nonce]);
                    try {
                        $id = $this->saleModel->createSale($data,$items);
                        $_SESSION['lab_sales'][$id] = $mode;
                        labRedirect('/sales/receipt/' . $id);
                    } catch (PDOException $e) { error_log($e->getMessage()); $data['errors'][]='No se pudo registrar la venta. No se guardó ningún cambio. Revise la estructura de Northwind y el registro de errores de Apache.'; }
                    catch (RuntimeException $e) { $data['errors'][]=$e->getMessage(); }
                }
            }
        }
        $data['nonce'] = bin2hex(random_bytes(16));
        $_SESSION['sale_nonces'][$data['nonce']] = $mode;
        if (count($_SESSION['sale_nonces'])>20) array_shift($_SESSION['sale_nonces']);
        $this->view('sales/form',$data);
    }
    public function receipt($id = null)
    {
        $id = labId($id);
        if (!isset($_SESSION['lab_sales'][$id])) { http_response_code(403); exit('Este comprobante no pertenece a las ventas de esta sesión.'); }
        $data = $this->saleModel->getReceipt($id);
        if (!$data['order']) { http_response_code(404); exit('Pedido no encontrado.'); }
        $data['title']='Comprobante de pedido #' . $id;
        $data['mode']=$_SESSION['lab_sales'][$id];
        $this->view('sales/receipt',$data);
    }
}
