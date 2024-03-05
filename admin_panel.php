<?php


// Includi la classe User, Database e UI, e il file di configurazione del database
require_once './User.php';
require_once './Database.php';
require_once './UI.php';
require_once './config.php';

// Inizializza la sessione
session_start();






// Recupera l'utente autenticato dalla sessione
$authenticatedUser = $_SESSION['user'];

// Instantiate the Database class
$database = Database::getInstance(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Inizializza l'oggetto UI passando l'istanza del database
$ui = new UI($database);

// Verifica se è stata inviata una richiesta POST (submit del form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera i dati dal form
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $citta = $_POST['citta'];

    // Crea un array con i dati
    $data = array('value1' => $nome, 'value2' => $cognome, 'value3' => $citta);

    // Specifica la tabella nel database dove inserire i dati (ad esempio 'datiutente')
    $tableName = 'datiutente';

    // Utilizza l'oggetto UI per creare un nuovo record
    $ui->createRecord($tableName, $data);
}

?>

<!-- Inizio del corpo della pagina -->
<div class="admin-panel-container">
<h2>Benvenuto, <?php echo isset($authenticatedUser['username']) ? htmlspecialchars($authenticatedUser['username']) : 'Utente'; ?>!</h2>

    <!-- Form per l'inserimento di dati sensibili -->
    <form action="admin_panel.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="cognome">Cognome:</label>
        <input type="text" id="cognome" name="cognome" required>

        <label for="citta">Città:</label>
        <input type="text" id="citta" name="citta" required>

        <button type="submit">Inserisci Dati Sensibili</button>
    </form>

    <!-- Altri elementi dell'interfaccia utente possono essere aggiunti secondo necessità -->

</div>
<!-- Fine del corpo della pagina -->

<?php
// Includi il footer
include_once './footer.php';
?>
