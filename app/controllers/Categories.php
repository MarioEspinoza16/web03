<?php

			class Categories extends Controller {

				private $categoryModel;

				public function __construct() {
					// Redirigir a login si no está autenticado
					if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== 'SI') {
						header('Location: ' . urlRoot . '/users/login');
						exit();
					}
					$this->categoryModel = $this->model('Category');
				}

				// Muestra la lista principal de categorías
				public function index() {
					$categories = $this->categoryModel->getCategories();
					$data = [
						'title' => 'Gestión de Categorías',
						'categories' => $categories
					];
					$this->view('categories/index', $data);
				}

				// Vista y procesamiento para crear categoría
				public function create() {
					if ($_SERVER['REQUEST_METHOD'] === 'POST') {
						$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

						$data = [
							'name' => trim($_POST['txtNombre']),
							'description' => trim($_POST['txtDescrip']),
							'imagen' => null,
							'mime' => null,
							'name_err' => ''
						];

						if (empty($data['name'])) {
							$data['name_err'] = 'Por favor ingrese el nombre de la categoría';
						}

						// Procesar la imagen si fue subida
						if (isset($_FILES['txtArchi']) && $_FILES['txtArchi']['error'] === UPLOAD_ERR_OK) {
							$data['imagen'] = file_get_contents($_FILES['txtArchi']['tmp_name']);
							$data['mime'] = $_FILES['txtArchi']['type'];
						}

						if (empty($data['name_err'])) {
							if ($this->categoryModel->addCategory($data)) {
								header('Location: ' . urlRoot . '/categories');
								exit();
							} else {
								die('Error al guardar la categoría.');
							}
						} else {
							$this->view('categories/inscateg', $data);
						}
					} else {
						$data = [
							'name' => '',
							'description' => '',
							'name_err' => ''
						];
						$this->view('categories/inscateg', $data);
					}
				}

				// Vista y procesamiento para modificar categoría
				public function edit($id) {
					if ($_SERVER['REQUEST_METHOD'] === 'POST') {
						$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

						$data = [
							'id' => $id,
							'name' => trim($_POST['txtNombre']),
							'description' => trim($_POST['txtDescrip']),
							'imagen' => null,
							'mime' => null,
							'name_err' => ''
						];

						if (empty($data['name'])) {
							$data['name_err'] = 'El nombre no puede estar vacío';
						}

						if (isset($_FILES['txtArchi']) && $_FILES['txtArchi']['error'] === UPLOAD_ERR_OK) {
							$data['imagen'] = file_get_contents($_FILES['txtArchi']['tmp_name']);
							$data['mime'] = $_FILES['txtArchi']['type'];
						}

						if (empty($data['name_err'])) {
							if ($this->categoryModel->updateCategory($data)) {
								header('Location: ' . urlRoot . '/categories');
								exit();
							} else {
								die('Error al actualizar.');
							}
						} else {
							$this->view('categories/modcateg', $data);
						}
					} else {
						$category = $this->categoryModel->getCategoryById($id);
						$data = [
							'id' => $category->CategoryID,
							'name' => $category->CategoryName,
							'description' => $category->Description,
							'has_image' => !empty($category->Imagen),
							'name_err' => ''
						];
						$this->view('categories/modcateg', $data);
					}
				}

				// Método para servir la imagen BLOB directamente con sus cabeceras HTTP
				public function image($id) {
					$category = $this->categoryModel->getCategoryById($id);
					if ($category && !empty($category->Imagen)) {
						header('Content-Type: ' . $category->Mime);
						echo $category->Imagen;
						exit();
					} else {
						// Imagen por defecto si no existe BLOB almacenado
						header('Content-Type: image/svg+xml');
						echo '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="gray" class="bi bi-image" viewBox="0 0 16 16"><path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/></svg>';
						exit();
					}
				}

				// Eliminar categoría
				public function delete($id) {
					if ($_SERVER['REQUEST_METHOD'] === 'POST') {
						if ($this->categoryModel->deleteCategory($id)) {
							header('Location: ' . urlRoot . '/categories');
							exit();
						}
					}
				}
			}	
			
		?>
