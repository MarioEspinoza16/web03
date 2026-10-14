<?php
// Conexión exclusiva de los módulos nuevos. No modifica Database.php.
class LabDatabase
{
    private $pdo;
    private $statement;
    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=' . db_Servidor . ';dbname=' . db_Basedato . ';charset=utf8mb4', db_Usuario, db_Contra, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }
    public function query($sql) { $this->statement = $this->pdo->prepare($sql); }
    public function bind($key, $value, $type = null)
    {
        if ($type === null) {
            $type = is_int($value) ? PDO::PARAM_INT : ($value === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        }
        $this->statement->bindValue($key, $value, $type);
    }
    public function execute() { return $this->statement->execute(); }
    public function resultSet() { $this->execute(); return $this->statement->fetchAll(); }
    public function singleRow() { $this->execute(); return $this->statement->fetch(); }
    public function begin() { $this->pdo->beginTransaction(); }
    public function commit() { $this->pdo->commit(); }
    public function rollback() { if ($this->pdo->inTransaction()) $this->pdo->rollBack(); }
    public function lastId() { return (int) $this->pdo->lastInsertId(); }
    public function affected() { return $this->statement->rowCount(); }
}
