<?php
require_once __DIR__ . '/../../config/db.php';

class User
{
  public function createUser($username, $email, $phone, $password)
  {
    global $pdo;
    try {
      $stmt = $pdo->prepare(
        "INSERT INTO users (id, username, email, phone, password, created_at) 
                    VALUES(nextval('users_id_seq'::regclass), :username, :email, :phone, :password, CURRENT_TIMESTAMP)"
      );
      return $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':phone' => $phone,
        ':password' => $password,
      ]);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getUserById($userId)
  {
    global $pdo;
    try {
      $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
      $stmt->execute([':id' => $userId]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getUserByUsername($username)
  {
    global $pdo;
    try {
      $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
      $stmt->execute([':username' => $username]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getUserByEmail($email)
  {
    global $pdo;
    try {
      $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
      $stmt->execute([':email' => $email]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getUserByPhone($phone)
  {
    global $pdo;
    try {
      $stmt = $pdo->prepare('SELECT * FROM users WHERE phone = :phone');
      $stmt->execute([':phone' => $phone]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getUserByEmailOrPhone($query)
  {
    global $pdo;
    try {
      $stmt = $pdo->prepare(
        'SELECT * FROM users WHERE email = :query OR phone = :query'
      );
      $stmt->execute([':query' => $query]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function updateUser(
    $userId,
    $username,
    $email,
    $phone,
    $password = null
  ) {
    global $pdo;
    try {
      $query =
        'UPDATE users SET username = :username, email = :email, phone = :phone';

      if ($password !== null) {
        $query .= ', password = :password';
      }

      $query .= ' WHERE id = :id';

      $stmt = $pdo->prepare($query);

      $params = [
        ':username' => $username,
        ':email' => $email,
        ':phone' => $phone,
        ':id' => $userId,
      ];

      if ($password !== null) {
        $params[':password'] = $password;
      }

      return $stmt->execute($params);
    } catch (PDOException $e) {
      return false;
    }
  }
}
