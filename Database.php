<?php
require_once './Database.php';
require_once './UI.php';
$database = Database::getInstance();

// Inizializza l'oggetto UI passando l'istanza del database
$ui = new UI($database);
class Database
{
    
    private static $instance;
    private $conn;

    private function __construct($host, $username, $password, $database)
    {
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Errore di connessione al database: " . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self('localhost', 'root', '', 'pannello');
        }
        return self::$instance;
    }

    public function executeQuery($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function createUser($dbname, $tableName, $username, $hashedPassword)
    {
        $query = "INSERT INTO $dbname.$tableName (username, password) VALUES (:username, :password)";
        $params = [':username' => $username, ':password' => $hashedPassword];
        $this->executeQuery($query, $params);
    }



    
}
?>
