<?php
require_once './config.php';
require_once './Database.php';
require_once './UI.php';
require_once './User.php';

session_start();

$database = Database::getInstance(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$ui = new UI($database);

// Navbar
echo '<nav>';
echo '<ul>';
echo '<li><a href="index.php">Home</a></li>';
echo '<li><a href="index.php?action=login">Login</a></li>';
echo '<li><a href="index.php?action=register">Register</a></li>';
echo '</ul>';
echo '</nav>';

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
