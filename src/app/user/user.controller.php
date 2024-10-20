<?php
require_once __DIR__ . '/user.service.php';

class UserController
{
  protected $userService;

  public function __construct()
  {
    $this->userService = new UserService();
  }

  public function showProfile()
  {
    $this->isLoggedIn();

    $user = $this->userService->getUserProfile($_SESSION['user_id']);
    require_once __DIR__ . '/../../views/profile.php';
  }

  public function handleUpdateProfile()
  {
    $this->isLoggedIn();

    $this->userService->updateProfile($_SESSION['user_id'], $_POST);
    header('Location: /profile');
  }

  private function isLoggedIn()
  {
    if (!isset($_SESSION['user_id'])) {
      header('Location: /login');
      exit();
    }
  }
}
