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
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'deleteRecord') {
            // Verifica se sono stati inviati i dati necessari per l'eliminazione
            if (isset($_POST['recordId']) && isset($tableName)) {
                $recordId = $_POST['recordId'];
                // Utilizza l'oggetto UI per eliminare il record
                $ui->deleteRecord($tableName, $recordId);
            } else {
                echo "Errore: Dati insufficienti per eliminare il record.";
            }
        }
    } else {
        // Recupera i dati dal form
        $pokemon = $_POST['pokemon'];
        $tipo = $_POST['tipo'];
        $livello = $_POST['livello'];

        // Crea un array con i dati
        $data = array('pokemon' => $pokemon, 'tipo' => $tipo, 'livello' => $livello);

        // Specifica la tabella nel database dove inserire i dati (ad esempio 'datiutente')
        $tableName = 'datiutente';

        // Utilizza l'oggetto UI per creare un nuovo record
        $ui->createRecord($tableName, $data);

        //post redirect get per evitare clone insert
        header("Location: admin_panel.php");
        exit();
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Il Box di Bill</title>
</head>
<div class="admin-panel-container">
<h2>Benvenuto, <?php echo isset($authenticatedUser['username']) ? htmlspecialchars($authenticatedUser['username']) : 'Utente'; ?>!</h2>

<div class="container mt-5">
    <h2>Il tuo BOX!</h2>
    <?php
    // Eseguire la query per ottenere i dati dalla tabella "datiutente"
    $query = "SELECT * FROM datiutente";
    $stmt = $database->executeQuery($query);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificare se ci sono record
    if (!empty($records)) {
        echo '<table class="table table-bordered mt-3">';
        echo '<thead>';
        echo '<tr>';
        foreach ($records[0] as $column => $value) {
            echo '<th>' . htmlspecialchars($column) . '</th>';
        }
        echo '<th>Elimina</th>'; // Aggiungi una colonna per le azioni
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($records as $record) {
            echo '<tr>';
            foreach ($record as $value) {
                echo '<td>' . htmlspecialchars($value) . '</td>';
            }
            // Aggiungi il pulsante "Elimina" con un form per ogni record
            echo '<td>
                    <form action="admin_panel.php" method="POST">
                        <input type="hidden" name="action" value="deleteRecord">
                        <input type="hidden" name="recordId" value="' . $record['id'] . '">
                        <button type="submit" class="btn btn-danger">Elimina</button>
                    </form>
                  </td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Nessun record trovato.</p>';
    }
    ?>

<h2>Deposita il tuo Pokemon</h2>


    <form action="admin_panel.php" method="POST">
        <label for="pokemon">Pokemon:</label>
        <input type="text" id="pokemon" name="pokemon" required>

        <label for="tipo">Tipo:</label>
        <input type="text" id="tipo" name="tipo" required>

        <label for="livello">Livello:</label>
<input type="number" id="livello" name="livello" required>


        <button type="submit">Deposita il Pokemon nel box (dati sensibili)</button>
    </form>

   
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<!-- Fine del corpo della pagina -->

<?php
// Includi il footer
include_once './footer.php';
?>
