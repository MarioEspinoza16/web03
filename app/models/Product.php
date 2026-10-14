<?php
require_once __DIR__ . '/../libraries/LabDatabase.php';
class Product
{
    private $db;
    public function __construct() { $this->db = new LabDatabase(); }
    public function getProducts($categoryId = null)
    {
        $sql = 'SELECT p.*, c.CategoryName, s.CompanyName FROM products p
                LEFT JOIN categories c ON p.CategoryID = c.CategoryID
                LEFT JOIN suppliers s ON p.SupplierID = s.SupplierID';
        if ($categoryId !== null) $sql .= ' WHERE p.CategoryID = :category';
        $this->db->query($sql . ' ORDER BY p.ProductName ASC, p.ProductID ASC');
        if ($categoryId !== null) $this->db->bind(':category', (int) $categoryId);
        return $this->db->resultSet();
    }
    public function getProductById($id)
    {
        $this->db->query('SELECT * FROM products WHERE ProductID = :id');
        $this->db->bind(':id', (int) $id);
        return $this->db->singleRow();
    }
    public function getCategories()
    {
        $this->db->query('SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryName');
        return $this->db->resultSet();
    }
    public function getCategoryById($id)
    {
        $this->db->query('SELECT CategoryID, CategoryName FROM categories WHERE CategoryID = :id');
        $this->db->bind(':id', (int) $id);
        return $this->db->singleRow();
    }
    public function getSuppliers()
    {
        $this->db->query('SELECT SupplierID, CompanyName FROM suppliers ORDER BY CompanyName');
        return $this->db->resultSet();
    }
    private function bindData($data)
    {
        foreach (['ProductName', 'SupplierID', 'CategoryID', 'QuantityPerUnit', 'UnitPrice', 'UnitsInStock', 'UnitsOnOrder', 'ReorderLevel', 'Discontinued'] as $field) {
            $value = $data[$field];
            if (in_array($field, ['SupplierID','CategoryID','UnitsInStock','UnitsOnOrder','ReorderLevel','Discontinued'], true)) $value = (int) $value;
            $this->db->bind(':' . $field, $value);
        }
    }
    public function addProduct($data)
    {
        $this->db->query('INSERT INTO products (ProductName,SupplierID,CategoryID,QuantityPerUnit,UnitPrice,UnitsInStock,UnitsOnOrder,ReorderLevel,Discontinued)
            VALUES (:ProductName,:SupplierID,:CategoryID,:QuantityPerUnit,:UnitPrice,:UnitsInStock,:UnitsOnOrder,:ReorderLevel,:Discontinued)');
        $this->bindData($data);
        return $this->db->execute();
    }
    public function updateProduct($data)
    {
        $this->db->query('UPDATE products SET ProductName=:ProductName,SupplierID=:SupplierID,CategoryID=:CategoryID,QuantityPerUnit=:QuantityPerUnit,
            UnitPrice=:UnitPrice,UnitsInStock=:UnitsInStock,UnitsOnOrder=:UnitsOnOrder,ReorderLevel=:ReorderLevel,Discontinued=:Discontinued WHERE ProductID=:id');
        $this->bindData($data);
        $this->db->bind(':id', (int) $data['ProductID']);
        return $this->db->execute();
    }
    public function deleteProduct($id)
    {
        $this->db->query('DELETE FROM products WHERE ProductID=:id');
        $this->db->bind(':id', (int) $id);
        try { $this->db->execute(); return $this->db->affected() === 1; }
        catch (PDOException $e) { if ((int) ($e->errorInfo[1] ?? 0) === 1451) return false; throw $e; }
    }
}
