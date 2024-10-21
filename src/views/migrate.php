<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Авторизация</title>
  <link rel="stylesheet" href="assets/styles/index.css">
</head>
<body>
  <main class="root">
    <div class="flex-flex flex-direction--column justify-center" style="flex-grow: 1; z-index: 9;">
      <div class="formbg-outer">
        <div class="formbg">
          <?php
          require __DIR__ . '/../config/db.php';

          if (isset($_POST['migrate'])) {
            $sql = "
        CREATE TABLE IF NOT EXISTS public.users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            phone VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";

            try {
              $pdo->exec($sql);
              echo 'Таблица users успешно создана!';
            } catch (PDOException $e) {
              echo 'Ошибка выполнения миграции: ' . $e->getMessage();
            }
          }
          ?>
          <form action="" method="POST">
            <button type="submit" name="migrate">Запустить миграцию</button>
          </form>
          <div class="footer-link padding-top--24">
            <span>Перейти к <a href="/login">авторизации</a></span>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>