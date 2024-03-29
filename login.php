<?php

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

?>

<!DOCTYPE html>
<html lang="en">
<head>  <link rel="stylesheet" href="./styles.css">


</head>
<body >
<h2 class="text-center">Login</h2>
    <div class="login-container text-center">
        
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>


