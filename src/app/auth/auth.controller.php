<?php
require_once __DIR__ . '/auth.service.php';

class AuthController
{
  protected $authService;

  public function __construct()
  {
    $config = require __DIR__ . '/../../../env.php';
    $this->authService = new AuthService($config);
  }

  public function register()
  {
    $this->isLoggedIn();

    require_once __DIR__ . '/../../views/register.php';
  }

  public function login()
  {
    $this->isLoggedIn();

    require_once __DIR__ . '/../../views/login.php';
  }

  public function handleLogin()
  {
    $postData = array_map('htmlspecialchars', $_POST);
    $this->authService->login($postData);
  }

  public function handleRegister()
  {
    $postData = array_map('htmlspecialchars', $_POST);
    $this->authService->register($postData);
  }

  public function logout()
  {
    $this->authService->logout();
    header('Location: /login');
  }

  private function isLoggedIn()
  {
    if (isset($_SESSION['user_id'])) {
      header('Location: /profile');
      exit();
    }
  }
}
