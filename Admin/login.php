<?php
// Admin/login.php
require_once 'admin_functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_input = trim($_POST['username']);
    $password_input = $_POST['password'];

    // Query the admins table (assumes columns: id, username, password (hashed))
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username_input]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password_input, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <label>Username:</label>
      <input type="text" name="username" required>
      <br><br>
      <label>Password:</label>
      <input type="password" name="password" required>
      <br><br>
      <button type="submit">Login</button>
    </form>
  </div>
  <footer>
    <p>&copy; <?php echo date("Y"); ?> SKYSHOP. All rights reserved.</p>
  </footer>
</body>
</html>
