<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        // Si ya está logueado, redirigir al inicio
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
            $this->redirect('/');
        }

        $error = $_GET['error'] ?? null;
        $this->view('auth/login', ['error' => $error]);
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $this->redirect('/login?error=' . urlencode('Debe completar todos los campos'));
        }

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['clave'])) {
            // Login exitoso
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['nombreyapellido'] = $user['NombreYApellido'];
            $_SESSION['EsAdmin'] = ($user['esAdmin'] == 1);
            $_SESSION['loggedIn'] = true;

            $this->redirect('/');
        } else {
            // Login fallido
            $this->redirect('/login?error=' . urlencode('Usuario y/o clave inválidos'));
        }
    }

    public function register()
    {
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
            $this->redirect('/');
        }
        
        $error = $_GET['error'] ?? null;
        $this->view('auth/register', ['error' => $error]);
    }

    public function store()
    {
         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $usuario = trim($_POST['usuario'] ?? '');
        $clave = trim($_POST['clave'] ?? '');
        $nomyapp = trim($_POST['nomyapp'] ?? '');
        
        if (empty($usuario) || empty($clave) || empty($nomyapp)) {
             $this->redirect('/register?error=' . urlencode('Debe completar todos los campos'));
        }

        $userModel = new User();
        $created = $userModel->create([
            'usuario' => $usuario,
            'clave' => $clave,
            'nomyapp' => $nomyapp
        ]);

        if ($created) {
            $this->redirect('/login?success=' . urlencode('Usuario creado exitosamente. Por favor inicie sesión.'));
        } else {
            $this->redirect('/register?error=' . urlencode('El usuario ya existe o hubo un error.'));
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }
}
