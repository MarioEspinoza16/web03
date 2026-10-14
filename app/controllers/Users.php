<?php

class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function login()
    {
        $data = [
            'usuario'   => '',
            'contra'    => '',
            'userError' => '',
            'passError' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['usuario'] = trim($_POST['txtUsua'] ?? '');
            $data['contra'] = $_POST['txtContra'] ?? '';

            if ($data['usuario'] === '') {
                $data['userError'] = 'El nombre del usuario es obligatorio';
            }

            if ($data['contra'] === '') {
                $data['passError'] = 'La contraseña es obligatoria';
            }

            if ($data['userError'] === '' && $data['passError'] === '') {
                $user = $this->userModel->login($data);

                if ($user) {
                    $this->createSession($user);
                } else {
                    $data['passError'] = 'Los datos de ingreso son incorrectos. Favor verificar';
                }
            }
        }

        $this->view('users/login', $data);
    }

    public function register()
    {
        $data = [
            'usuario'    => '',
            'contra'     => '',
            'recontra'   => '',
            'userError'  => '',
            'passError'  => '',
            'passError2' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['usuario'] = trim($_POST['txtUsua'] ?? '');
            $data['contra'] = $_POST['txtContra'] ?? '';
            $data['recontra'] = $_POST['txtContra2'] ?? '';

            $validaNombre = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u';
            $validaContra = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9\s]).{8,}$/';

            if ($data['usuario'] === '') {
                $data['userError'] = 'El nombre del usuario es obligatorio';
            } elseif (!preg_match($validaNombre, $data['usuario'])) {
                $data['userError'] = 'El nombre de usuario solo puede contener letras y espacios';
            }

            if ($data['contra'] === '') {
                $data['passError'] = 'La contraseña es obligatoria';
            } elseif (strlen($data['contra']) < 8) {
                $data['passError'] = 'La contraseña debe tener al menos 8 caracteres';
            } elseif (!preg_match($validaContra, $data['contra'])) {
                $data['passError'] = 'La contraseña debe incluir una mayúscula, una minúscula, un número y un carácter especial';
            }

            if ($data['recontra'] === '') {
                $data['passError2'] = 'La contraseña de confirmación es obligatoria';
            } elseif ($data['contra'] !== $data['recontra']) {
                $data['passError2'] = 'Las contraseñas no coinciden';
            }

            if (
                $data['userError'] === '' &&
                $data['passError'] === '' &&
                $data['passError2'] === ''
            ) {
                $data['contra'] = password_hash(
                    $data['contra'],
                    PASSWORD_DEFAULT
                );

                if ($this->userModel->register($data)) {
                    header('Location: ' . urlRoot . '/users/login');
                    exit;
                } else {
                    die('No se pudo registrar el usuario');
                }
            }
        }

        $this->view('users/register', $data);
    }

public function createSession($user)
{
    session_regenerate_id(true);

    $_SESSION['autenticado'] = 'SI';
    $_SESSION['usuario'] = $user->nombre;

    header('Location: ' . urlRoot . '/pages/index');
    exit;
}

    public function logout()
    {
        unset($_SESSION['autenticado']);
        unset($_SESSION['usuario']);

        header('Location: ' . urlRoot . '/pages/index');
        exit;
    }
}