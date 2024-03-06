<?php
require_once './User.php';
require_once './Database.php';
require_once './UI.php';
require_once './config.php';

// Inizializza la sessione
session_start();

// Istanzia la classe Database
$database = Database::getInstance(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Recupera l'utente autenticato dalla sessione
$authenticatedUser = $_SESSION['user'];

// Istanzia l'oggetto UI passando l'istanza del database
$ui = new UI($database);

// Verifica se è stata inviata una richiesta POST (submit del form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'deleteRecord') {
            // Codice per l'eliminazione del record...
        } elseif ($_POST['action'] === 'updateRecord') {
            // Verifica se sono stati inviati i dati necessari per l'aggiornamento
            if (isset($_POST['recordId'], $_POST['editColumns'])) {
                $recordId = $_POST['recordId'];
                $editColumns = $_POST['editColumns'];
                $newData = [];

                // Costruisci l'array $newData con le colonne da aggiornare
                foreach ($editColumns as $columnName) {
                    $newData[$columnName] = $_POST['new' . ucfirst($columnName)];
                }

                $tableName = 'datiutente';

                // Utilizza l'oggetto UI per aggiornare il record
                $ui->updateRecord($tableName, $recordId, $newData);
            } else {
                echo "Errore: Dati insufficienti per aggiornare il record.";
            }
        }
    } elseif (isset($_POST['pokemon'], $_POST['tipo'], $_POST['livello'])) {
        // Recupera i dati dal form
        $pokemon = $_POST['pokemon'];
        $tipo = $_POST['tipo'];
        $livello = $_POST['livello'];

        // Crea un array con i dati
        $data = ['pokemon' => $pokemon, 'tipo' => $tipo, 'livello' => $livello];

        // Specifica la tabella nel database dove inserire i dati (ad esempio 'datiutente')
        $tableName = 'datiutente';

        // Utilizza l'oggetto UI per creare un nuovo record
        $ui->createRecord($tableName, $data);

        // Post redirect get per evitare clone insert
        header("Location: admin_panel.php");
        exit();
    }
}
?>
<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="./styles.css">
    
    <title>PokeBanca</title>
</head>
<body >
<div class="admin-panel-container">
    <h2>Benvenuto, <?php echo isset($authenticatedUser['username']) ? htmlspecialchars($authenticatedUser['username']) : 'Utente'; ?>!</h2>

    <div class="container mt-5 ">
        <h2>Il tuo BOX!</h2>
        <?php
        // Esegui la query per ottenere i dati dalla tabella "datiutente"
        $query = "SELECT * FROM datiutente";
        $stmt = $database->executeQuery($query);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verifica se ci sono record
        if (!empty($records)) {
            echo '<table class="table table-bordered mt-3 pokeball">';
            echo '<thead>';
            echo '<tr>';
            // Intestazioni della tabella
            foreach ($records[0] as $columnName => $value) {
                echo '<th>' . htmlspecialchars($columnName) . '</th>';
            }
            echo '<th>Modifica</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            // Dati della tabella
            foreach ($records as $record) {
                echo '<tr>';
                foreach ($record as $columnName => $value) {
                    echo '<td>';
                    // Verifica se il record è in modalità di modifica
                    if (isset($record['isEditing']) && $record['isEditing'] && isset($record['editColumn']) && $record['editColumn'] === $columnName) {
                        // Se il record è in modalità di modifica, mostra l'input
                        echo '<input type="text" name="new' . ucfirst($columnName) . '" value="' . htmlspecialchars($value) . '">';
                    } else {
                        // Se il record non è in modalità di modifica, mostra il valore
                        echo htmlspecialchars($value);
                    }
                    echo '</td>';
                }
                // Aggiorna il form per includere il recordId
                echo '<td>';
                echo '<form action="admin_panel.php" method="POST">';
                echo '<input type="hidden" name="action" value="deleteRecord">';
                echo '<input type="hidden" name="recordId" value="' . $record['id'] . '">';
                echo '<button type="submit" class="btn btn-danger">Elimina</button>';
                echo '</form>';
                echo '<form action="admin_panel.php" method="POST">';
                echo '<input type="hidden" name="action" value="updateRecord">';
                echo '<input type="hidden" name="recordId" value="' . $record['id'] . '">';
                ?>
                <input type="hidden" name="action" value="updateRecord">
                <!-- Aggiorna il form per includere le colonne del record -->
                <?php
                foreach ($record as $columnName => $value) {
                    if ($columnName !== 'isEditing' && $columnName !== 'editColumn' && $columnName !== 'id') {
                        echo '<input type="hidden" name="editColumns[]" value="' . $columnName . '">';
                    }
                }
                ?>
                <!-- Aggiungi gli input per le colonne da modificare -->
                <label for="newPokemon">Nuovo Pokemon:</label>
                <input type="text" id="newPokemon" name="newPokemon" value="<?php echo htmlspecialchars($record['pokemon']); ?>" required>

                <label for="newTipo">Nuovo Tipo:</label>
                <input type="text" id="newTipo" name="newTipo" value="<?php echo htmlspecialchars($record['tipo']); ?>" required>

                <label for="newLivello">Nuovo Livello:</label>
                <input type="number" id="newLivello" name="newLivello" value="<?php echo htmlspecialchars($record['livello']); ?>" required>
                
                <?php
                if (isset($record['isEditing']) && $record['isEditing']) {
                    echo '<button type="submit" class="btn btn-primary">Salva</button>';
                } else {
                    echo '<button type="submit" class="btn btn-warning">Modifica</button>';
                }
                ?>
                </form>
                </td>
                </tr>
            <?php
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Nessun record trovato.</p>';
        }
        ?>

        
    </div>

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

<!-- Includi gli script necessari, ad esempio Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include_once('footer.php'); ?>
</html>