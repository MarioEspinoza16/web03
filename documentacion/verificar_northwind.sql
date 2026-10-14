-- Consultas de verificación de la estructura y los registros de Northwind.
-- Estas consultas no modifican las tablas ni los datos.
-- Estas consultas no modifican las tablas ni los datos.
SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'northwind'
AND LOWER(TABLE_NAME) IN ('categories','suppliers','products','customers','employees','shippers','orders','order_details','order details');
SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'northwind'
AND LOWER(TABLE_NAME) IN ('orders','order_details','order details')
ORDER BY TABLE_NAME, ORDINAL_POSITION;
SELECT ProductID, ProductName, UnitsInStock, UnitPrice FROM northwind.products ORDER BY ProductName;
SELECT CustomerID, CompanyName FROM northwind.customers ORDER BY CompanyName;
SELECT EmployeeID, FirstName, LastName FROM northwind.employees ORDER BY FirstName;
SELECT ShipperID, CompanyName FROM northwind.shippers ORDER BY CompanyName;
