<?php
class User
{

    
    private $username;
    private $password;
    private static $instance = null;
    private $db;

 

    public static function getInstance()
    {
        // Utilizza il costruttore privato solo se non esiste un'istanza
        if (self::$instance == null) {
            self::$instance = new User();
        }
        return self::$instance;
    }

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hashedPassword;
    }

    public function register()
{
    if (!$this->isUserExists()) {
        $this->db->createUser('pannello', 'users', $this->username, $this->password);
        return true; // Registrazione riuscita
    } else {
        return false; // Utente già esistente
    }
}

    public function authenticate()
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $params = [':username' => $this->username];

        $stmt = $this->db->executeQuery($query, $params);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($this->password, $user['password'])) {
            echo "Utente autenticato correttamente";
            return $user;
        } else {
            echo "Credenziali non valide";
            return false;
        }
    }

    private function isUserExists()
    {
        $query = "SELECT COUNT(*) FROM users WHERE username = :username";
        $params = [':username' => $this->username];

        $stmt = $this->db->executeQuery($query, $params);
        $count = $stmt->fetchColumn();

        return ($count > 0);
    }

    public function getHashedPassword()
    {
        return $this->password;
    }

    public function getUserDetails()
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $params = [':username' => $this->username];

        $stmt = $this->db->executeQuery($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
