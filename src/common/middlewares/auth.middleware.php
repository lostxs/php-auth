<?php
class AuthMiddleware
{
  public static function requireGuest()
  {
    if (isset($_SESSION['user_id'])) {
      header('Location: /profile');
      exit();
    }
  }

  public static function requireAuth()
  {
    if (!isset($_SESSION['user_id'])) {
      header('Location: /login');
      exit();
    }
  }
}
