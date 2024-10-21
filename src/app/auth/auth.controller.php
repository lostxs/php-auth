<?php
require_once __DIR__ . '/auth.service.php';
require_once __DIR__ . '/../../common/middlewares/auth.middleware.php';

class AuthController
{
  protected $authService;

  public function __construct()
  {
    $config = require __DIR__ . '/../../../env.php';
    $this->authService = new AuthService($config);
  }

  public function register(): void
  {
    AuthMiddleware::requireGuest();
    require_once __DIR__ . '/../../views/register.php';
  }

  public function login(): void
  {
    AuthMiddleware::requireGuest();
    require_once __DIR__ . '/../../views/login.php';
  }

  public function handleLogin(): void
  {
    $postData = array_map('htmlspecialchars', $_POST);
    $this->authService->login($postData);
  }

  public function handleRegister(): void
  {
    $postData = array_map('htmlspecialchars', $_POST);
    $this->authService->register($postData);
  }

  public function logout(): void
  {
    $this->authService->logout();
    header('Location: /login');
  }
}
