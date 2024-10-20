<?php
class Redirect
{
  public static function redirectWithMessage(
    string $location,
    string $message,
    string $type = 'error'
  ): void {
    $_SESSION['message'] = ['text' => $message, 'type' => $type];
    header("Location: $location");
    exit();
  }
}
