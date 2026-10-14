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
        $this->db->query(
            'SELECT usuario AS nombre, contra AS contrasena
             FROM usuarios'
        );

        return $this->db->resultSet();
    }

    public function login($data)
    {
        $this->db->query(
            'SELECT usuario AS nombre, contra AS contrasena
             FROM usuarios
             WHERE usuario = :usuario'
        );

        $this->db->bind(':usuario', $data['usuario']);
        $user = $this->db->singleRow();

        if (!$user) {
            return false;
        }

        return password_verify($data['contra'], $user->contrasena)
            ? $user
            : false;
    }

    public function register($data)
    {
        $this->db->query(
            'INSERT INTO usuarios (usuario, contra)
             VALUES (:usuario, :contra)'
        );

        $this->db->bind(':usuario', $data['usuario']);
        $this->db->bind(':contra', $data['contra']);

        return $this->db->execute();
    }
}