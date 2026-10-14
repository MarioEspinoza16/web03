<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUsers()
    {
        $this->db->query('SELECT * FROM usuarios');
        return $this->db->resultSet();
    }

    public function login($data)
    {
        $this->db->query(
            'SELECT * FROM usuarios WHERE nombre = :nomb'
        );

        $this->db->bind(':nomb', $data['usuario']);
        $usuario = $this->db->singleRow();

        if (!$usuario) {
            return false;
        }

        if (password_verify($data['contra'], $usuario->contrasena)) {
            return $usuario;
        }

        return false;
    }

    public function register($data)
    {
        $this->db->query(
            'INSERT INTO usuarios (nombre, contrasena)
             VALUES (:nomb, :cont)'
        );

        $this->db->bind(':nomb', $data['usuario']);
        $this->db->bind(':cont', $data['contra']);

        return $this->db->execute();
    }
}