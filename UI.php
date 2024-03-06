<?php
class UI
{

    
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    
    

    public function createRecord($table, $data)
    {
        // Esempio di creazione di un record nel database
        $query = "INSERT INTO $table (pokemon, tipo,livello) VALUES (:pokemon, :tipo, :livello)";
        $params = array(':pokemon' => $data['pokemon'], ':tipo' => $data['tipo'], ':livello' => $data['livello']);
        $this->database->executeQuery($query, $params);
    }
    
    public function deleteRecord($table, $recordId)
    {
        // Esempio di eliminazione di un record nel database
        $query = "DELETE FROM $table WHERE id = :recordId";
        $params = [':recordId' => $recordId];
        $this->database->executeQuery($query, $params);
    }
}
?>
