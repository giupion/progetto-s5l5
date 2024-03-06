<?php
require_once './config.php';
require_once './Database.php';
require_once './UI.php';
require_once './User.php';

session_start();

$database = Database::getInstance(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$ui = new UI($database);

// Navbar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Il Box di Bill</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=register">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php

// Gestione del routing
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
    case 'login':
        require_once 'login.php';
        break;

    case 'register':
        require_once 'register.php';
        break;

    case 'home':
    default:
        // Pagina predefinita, ad esempio una dashboard
        require_once 'index.php';
        break;
}
?>
