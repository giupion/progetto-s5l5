<?php
ob_start();
include_once './header.php';
require_once './User.php';
require_once './Database.php';

session_start();

$database = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User($database);
    $user->setUsername($username);
    $user->setPassword($password);

    if ($user->register()) {
        // Utente registrato con successo, reindirizza alla pagina di login
        header("Location: login.php?registration_success=1");
        exit();
    } else {
        $errorMessage = "Username già esistente. Scegli un altro username.";
    }
}

include_once './footer.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... -->
</head>
<body>
    <div class="register-container">
        <h2>Registrazione</h2>
        <?php if (isset($errorMessage)) : ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <?php if (isset($successMessage)) : ?>
            <p class="success"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
           <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
            <button type="submit">Registrati</button>
        </form>
    </div>
</body>
</html>