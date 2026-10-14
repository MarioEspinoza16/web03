<?php
require_once __DIR__ . '/../libraries/LabDatabase.php';
class Sale
{
    private $db;
    private $detailTable;
    public function __construct()
    {
        $this->db = new LabDatabase();
        $this->db->query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND LOWER(TABLE_NAME) IN ('order_details','order details')");
        $tables = $this->db->resultSet();
        if (!$tables) throw new RuntimeException('Northwind debe incluir la tabla Order_Details.');
        $this->detailTable = $tables[0]->TABLE_NAME;
    }
    public function getCustomers()
    {
        $this->db->query('SELECT CustomerID, CompanyName, ContactName FROM customers ORDER BY CompanyName');
        return $this->db->resultSet();
    }
    public function getEmployees()
    {
        $this->db->query('SELECT EmployeeID, FirstName, LastName FROM employees ORDER BY FirstName,LastName');
        return $this->db->resultSet();
    }
    public function getShippers()
    {
        $this->db->query('SELECT ShipperID, CompanyName FROM shippers ORDER BY CompanyName');
        return $this->db->resultSet();
    }
    private function getCustomer($id)
    {
        $this->db->query('SELECT * FROM customers WHERE CustomerID=:id');
        $this->db->bind(':id', $id);
        return $this->db->singleRow();
    }
    private function columns($table)
    {
        $this->db->query('SELECT COLUMN_NAME,IS_NULLABLE,COLUMN_DEFAULT,EXTRA,DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=:table');
        $this->db->bind(':table', $table);
        return $this->db->resultSet();
    }
    private function insertRow($table, $values)
    {
        $actual = [];
        foreach ($this->columns($table) as $column) {
            $field = $column->COLUMN_NAME;
            if (stripos($column->EXTRA, 'auto_increment') !== false) continue;
            if (array_key_exists($field, $values)) {
                $value = $values[$field];
                if ($value === null && $column->IS_NULLABLE === 'NO') {
                    throw new RuntimeException("El campo $table.$field debe admitir NULL para este proceso. Revise la estructura de Northwind antes de continuar.");
                }
                $actual[$field] = $value;
            } elseif ($column->IS_NULLABLE === 'NO' && $column->COLUMN_DEFAULT === null) {
                throw new RuntimeException("El campo obligatorio $table.$field no está contemplado por esta versión de Northwind.");
            }
        }
        $fields = array_keys($actual);
        $quoted = array_map(fn($field)=>'`' . str_replace('`','``',$field) . '`', $fields);
        $params = array_map(fn($i)=>':v' . $i, array_keys($fields));
        $this->db->query('INSERT INTO `' . str_replace('`','``',$table) . '` (' . implode(',',$quoted) . ') VALUES (' . implode(',',$params) . ')');
        foreach (array_values($actual) as $i=>$value) $this->db->bind(':v' . $i, $value);
        $this->db->execute();
    }
    public function createSale($data, $items)
    {
        $customer = $this->getCustomer($data['CustomerID']);
        if (!$customer) throw new RuntimeException('Cliente no encontrado.');
        $this->db->query('SELECT ShipperID FROM shippers WHERE ShipperID=:id');
        $this->db->bind(':id', (int) $data['ShipVia']);
        if (!$this->db->singleRow()) throw new RuntimeException('Seleccione un transportista existente.');
        $employee = $data['mode'] === 'assisted' ? (int) $data['EmployeeID'] : null;
        if ($employee !== null) {
            $this->db->query('SELECT EmployeeID FROM employees WHERE EmployeeID=:id');
            $this->db->bind(':id', $employee);
            if (!$this->db->singleRow()) throw new RuntimeException('Seleccione un empleado existente.');
        } else {
            // Algunas versiones de Northwind exigen EmployeeID: se asigna responsable administrativo.
            foreach ($this->columns('orders') as $column) {
                if ($column->COLUMN_NAME === 'EmployeeID' && $column->IS_NULLABLE === 'NO') {
                    $employees = $this->getEmployees();
                    if (!$employees) throw new RuntimeException('Northwind requiere un empleado responsable.');
                    $employee = (int) $employees[0]->EmployeeID;
                }
            }
        }
        $this->db->query("SELECT TABLE_NAME,ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND (LOWER(TABLE_NAME) IN ('orders','products') OR TABLE_NAME=:details)");
        $this->db->bind(':details', $this->detailTable);
        $engines = $this->db->resultSet();
        if (count($engines) !== 3) throw new RuntimeException('Faltan tablas de ventas en Northwind.');
        foreach ($engines as $table) if (strtoupper($table->ENGINE ?? '') !== 'INNODB') throw new RuntimeException('Las tablas de ventas deben usar InnoDB para guardar la venta completa.');
        ksort($items, SORT_NUMERIC);
        $this->db->begin();
        try {
            $products = [];
            foreach ($items as $id=>$quantity) {
                $this->db->query('SELECT ProductID,ProductName,UnitPrice,UnitsInStock,Discontinued FROM products WHERE ProductID=:id FOR UPDATE');
                $this->db->bind(':id', (int) $id);
                $product = $this->db->singleRow();
                if (!$product || $product->Discontinued) throw new RuntimeException('Uno de los productos no está disponible.');
                if ($quantity > $product->UnitsInStock) throw new RuntimeException('Existencias insuficientes para ' . $product->ProductName . '.');
                $products[$id] = $product;
            }
            $this->insertRow('orders', [
                'CustomerID'=>$customer->CustomerID, 'EmployeeID'=>$employee,
                'OrderDate'=>date('Y-m-d H:i:s'), 'RequiredDate'=>$data['RequiredDate'] . ' 00:00:00',
                'ShippedDate'=>$data['ShippedDate'] . ' 00:00:00', 'ShipVia'=>(int) $data['ShipVia'], 'Freight'=>'0.0000',
                'ShipName'=>$customer->CompanyName, 'ShipAddress'=>$customer->Address ?? '',
                'ShipCity'=>$customer->City ?? '', 'ShipRegion'=>$customer->Region ?? '',
                'ShipPostalCode'=>$customer->PostalCode ?? '', 'ShipCountry'=>$customer->Country ?? ''
            ]);
            $orderId = $this->db->lastId();
            foreach ($items as $id=>$quantity) {
                $this->insertRow($this->detailTable, [
                    'OrderID'=>$orderId, 'ProductID'=>(int) $id, 'UnitPrice'=>(string) $products[$id]->UnitPrice,
                    'Quantity'=>$quantity, 'Discount'=>sprintf('%.2F', $data['mode'] === 'assisted' ? (float) $data['Discount']/100 : 0)
                ]);
                $this->db->query('UPDATE products SET UnitsInStock=UnitsInStock-:quantity WHERE ProductID=:id');
                $this->db->bind(':quantity', $quantity); $this->db->bind(':id', (int) $id); $this->db->execute();
            }
            $this->db->commit();
            return $orderId;
        } catch (Throwable $e) { $this->db->rollback(); throw $e; }
    }
    public function getReceipt($id)
    {
        $this->db->query('SELECT o.*,c.CompanyName AS CustomerName,e.FirstName,e.LastName,s.CompanyName AS ShipperName
            FROM orders o LEFT JOIN customers c ON o.CustomerID=c.CustomerID
            LEFT JOIN employees e ON o.EmployeeID=e.EmployeeID LEFT JOIN shippers s ON o.ShipVia=s.ShipperID WHERE o.OrderID=:id');
        $this->db->bind(':id', (int) $id); $order = $this->db->singleRow();
        $table = '`' . str_replace('`','``',$this->detailTable) . '`';
        $this->db->query('SELECT d.*,p.ProductName,ROUND(d.UnitPrice*d.Quantity*(1-d.Discount),4) AS Subtotal FROM ' . $table . ' d JOIN products p ON d.ProductID=p.ProductID WHERE d.OrderID=:id ORDER BY p.ProductName');
        $this->db->bind(':id',(int) $id); $details = $this->db->resultSet();
        $this->db->query('SELECT ROUND(SUM(UnitPrice*Quantity*(1-Discount)),4) AS Total FROM ' . $table . ' WHERE OrderID=:id');
        $this->db->bind(':id',(int) $id); $total = $this->db->singleRow();
        return ['order'=>$order,'details'=>$details,'total'=>$total->Total ?? '0.0000'];
    }
}
