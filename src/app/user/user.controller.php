<?php
require_once __DIR__ . '/user.service.php';
require_once __DIR__ . '/../../common/middlewares/auth.middleware.php';

class UserController
{
  protected $userService;

  public function __construct()
  {
    $this->userService = new UserService();
  }

  public function showProfile()
  {
    AuthMiddleware::requireAuth();
    $user = $this->userService->getUserProfile($_SESSION['user_id']);
    require_once __DIR__ . '/../../views/profile.php';
  }

  public function handleUpdateProfile()
  {
    AuthMiddleware::requireAuth();
    $this->userService->updateProfile($_SESSION['user_id'], $_POST);
  }
}
