<?php
require_once __DIR__ . '/../helpers/lab_helper.php';
class Products extends Controller
{
    private $productModel;
    public function __construct()
    {
        if (($_SESSION['autenticado'] ?? '') !== 'SI') labRedirect('/users/login');
        $this->productModel = $this->model('Product');
    }
    public function index($categoryId = null)
    {
        $title = 'Lista general de productos';
        if ($categoryId !== null) {
            $categoryId = labId($categoryId);
            $category = $this->productModel->getCategoryById($categoryId);
            if (!$category) { http_response_code(404); exit('Categoría no encontrada.'); }
            $title = 'Lista de productos por la categoría ' . $category->CategoryName;
        }
        $data = ['title'=>$title, 'products'=>$this->productModel->getProducts($categoryId), 'message'=>$_SESSION['product_message'] ?? ''];
        unset($_SESSION['product_message']);
        $this->view('products/index', $data);
    }
    public function create() { $this->form(); }
    public function edit($id = null) { $this->form(labId($id)); }
    private function form($id = null)
    {
        $data = ['ProductID'=>$id, 'ProductName'=>'', 'SupplierID'=>'', 'CategoryID'=>'', 'QuantityPerUnit'=>'', 'UnitPrice'=>'0.0000',
                 'UnitsInStock'=>0,'UnitsOnOrder'=>0,'ReorderLevel'=>0,'Discontinued'=>0,'errors'=>[]];
        if ($id !== null) {
            $product = $this->productModel->getProductById($id);
            if (!$product) { http_response_code(404); exit('Producto no encontrado.'); }
            $data = array_merge($data, (array) $product);
        }
        $data['categories'] = $this->productModel->getCategories();
        $data['suppliers'] = $this->productModel->getSuppliers();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            labCheckPost();
            foreach (['ProductName','SupplierID','CategoryID','QuantityPerUnit','UnitPrice','UnitsInStock','UnitsOnOrder','ReorderLevel'] as $field) $data[$field] = labPost($field);
            $data['Discontinued'] = labPost('Discontinued') === '1' ? 1 : 0;
            foreach (['ProductName'=>40,'QuantityPerUnit'=>20] as $field=>$limit) {
                $length = preg_match_all('/./us', $data[$field]);
                if ($data[$field] === '' || $length === false || $length > $limit) $data['errors'][] = "El campo " . ($field==='ProductName'?'nombre':'presentación') . " es obligatorio y admite hasta $limit caracteres.";
            }
            foreach (['UnitsInStock','UnitsOnOrder','ReorderLevel'] as $field) {
                if (filter_var($data[$field], FILTER_VALIDATE_INT, ['options'=>['min_range'=>0,'max_range'=>32767]]) === false) $data['errors'][] = 'Las cantidades deben ser enteros entre 0 y 32767.';
            }
            if (!preg_match('/^\d{1,15}(\.\d{1,4})?$/D', $data['UnitPrice'])) $data['errors'][] = 'El precio debe ser positivo o cero, con hasta cuatro decimales. Use punto decimal.';
            foreach (['CategoryID'=>'categories','SupplierID'=>'suppliers'] as $field=>$list) {
                $ids = array_map(fn($row)=>(string) $row->$field, $data[$list]);
                if (!in_array($data[$field], $ids, true)) $data['errors'][] = 'Seleccione una categoría y un proveedor existentes.';
            }
            if (!$data['errors']) {
                try {
                    $ok = $id === null ? $this->productModel->addProduct($data) : $this->productModel->updateProduct($data);
                    if ($ok) { $_SESSION['product_message'] = 'Producto guardado correctamente.'; labRedirect('/products'); }
                    $data['errors'][] = 'No se pudo guardar el producto.';
                } catch (PDOException $e) { error_log($e->getMessage()); $data['errors'][] = 'No se pudo guardar. Verifique que el proveedor y la categoría sigan disponibles.'; }
            }
        }
        $data['title'] = $id === null ? 'Agregar producto' : 'Modificar producto';
        $this->view('products/form', $data);
    }
    public function delete($id = null)
    {
        labCheckPost();
        $id = labId($id);
        $_SESSION['product_message'] = $this->productModel->deleteProduct($id) ? 'Producto eliminado.' : 'No se pudo eliminar: el producto no existe o está relacionado con ventas. Puede marcarlo como descontinuado.';
        labRedirect('/products');
    }
}
