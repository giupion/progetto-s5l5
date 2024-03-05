<?php
class UI
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    // Metodi per gestire l'interfaccia utente e operazioni CRUD
    // ...

    

    public function createRecord($table, $data)
    {
        // Esempio di creazione di un record nel database
        $query = "INSERT INTO $table (nome, cognome, citta) VALUES (:nome, :cognome, :citta)";
        $params = array(':nome' => $data['nome'], ':cognome' => $data['cognome'], ':citta' => $data['citta']);
        $this->database->executeQuery($query, $params);
    }
    // ...
}
?>
