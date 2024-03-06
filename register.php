<?php

require_once './User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User($database);
    $user->setUsername($username);
    $user->setPassword($password);

    if ($user->register()) {
        // Utente registrato con successo, reindirizza alla pagina di login
        header("Location: index.php?action=login&registration_success=1");
        exit();
    } else {
        $errorMessage = "Username già esistente. Scegli un altro username.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="./styles.css">


</head>
<body >

    <div class="register-container text-center">
    <h2 class="my-1">Registrazione</h2>
        <?php if (isset($errorMessage)) : ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <?php if (isset($successMessage)) : ?>
            <p class="success"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <form  action="index.php?action=register" method="POST">
            <label for="username">Username:</label>
            <input class="my-1 mx2 "type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input class="my-1 mx-2" type="password" id="password" name="password" required>
            <button type="submit">Registrati</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>