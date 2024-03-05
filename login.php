<?php
require_once './header.php';
require_once './User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User($database);
    $user->setUsername($username);

    // Verifica se l'utente esiste e ottieni i dettagli
    $userDetails = $user->getUserDetails();

    if ($userDetails && password_verify($password, $userDetails['password'])) {
        // Memorizza l'utente autenticato nella sessione
        $_SESSION['user'] = $userDetails;

        // Reindirizza alla pagina di amministrazione
        header("Location: admin_panel.php");
        exit();
    } else {
        // Credenziali non valide
        $errorMessage = "Credenziali non valide.";
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
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($errorMessage)) : ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form action="index.php?action=login" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>