<?php
			class Category {
				private $db;

				public function __construct() {
					$this->db = new Database(); 
				}

				// Obtener todas las categorías
				public function getCategories() {
					$this->db->query('SELECT CategoryID, CategoryName, Description, Mime FROM categories ORDER BY CategoryID ASC');
					return $this->db->resultSet();
				}

				// Obtener categoría por ID
				public function getCategoryById($id) {
					$this->db->query('SELECT * FROM categories WHERE CategoryID = :id');
					$this->db->bind(':id', $id);
					return $this->db->singleRow();
				}

				// Insertar nueva categoría con soporte de imagen opcional
				public function addCategory($data) {
					if (!empty($data['imagen'])) {
						$this->db->query('INSERT INTO categories (CategoryName, Description, Imagen, Mime) VALUES (:name, :desc, :img, :mime)');
						$this->db->bind(':img', $data['imagen']);
						$this->db->bind(':mime', $data['mime']);
					} else {
						$this->db->query('INSERT INTO categories (CategoryName, Description) VALUES (:name, :desc)');
					}

					$this->db->bind(':name', $data['name']);
					$this->db->bind(':desc', $data['description']);

					return $this->db->execute();
				}

				// Actualizar categoría e imagen en la BD
				public function updateCategory($data) {
					if (!empty($data['imagen'])) {
						$this->db->query('UPDATE categories SET CategoryName = :name, Description = :desc, Imagen = :img, Mime = :mime WHERE CategoryID = :id');
						$this->db->bind(':img', $data['imagen']);
						$this->db->bind(':mime', $data['mime']);
					} else {
						$this->db->query('UPDATE categories SET CategoryName = :name, Description = :desc WHERE CategoryID = :id');
					}

					$this->db->bind(':id', $data['id']);
					$this->db->bind(':name', $data['name']);
					$this->db->bind(':desc', $data['description']);

					return $this->db->execute();
				}

				// Eliminar categoría
				public function deleteCategory($id) {
					$this->db->query('DELETE FROM categories WHERE CategoryID = :id');
					$this->db->bind(':id', $id);
					return $this->db->execute();
				}
			}
