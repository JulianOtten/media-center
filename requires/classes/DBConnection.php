<?php
require_once 'Logger.php';

class DBConnection {
    public $db_host;
    public $db_user;
    private $db_password;
    public $db_name;
    private $db_conn;
    private $statement;
    private $log;
    private $query;

    public function __construct($host, $user, $password, $name) {
        if(!$this->ValidateInfo($host, $user, $name)){
            throw new Exception("One of the giving arguments isn't a string or has no content...");
        }
        $this->db_host = $host;
        $this->db_user = $user;
        $this->db_password = $password;
        $this->db_name = $name;
        $this->log = new Logger();
        $this->connect();
    }

    protected function connect() {
        try {
            $db_conn = new PDO("mysql:dbname=$this->db_name;host=$this->db_host", $this->db_user, $this->db_password);
            $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db_conn = $db_conn;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function prepare($query) {
        $this->query = $query;
        $conn = $this->db_conn;
        $stmt = $conn->prepare($query);
        $this->statement = $stmt;
    }

    public function bindParam($param, $value, $type) {
        $stmt = $this->statement;
        if(isset($type)) $stmt->bindParam($param, $value, $type);
        else $stmt->bindParam($param, $value);
        $this->log->info($param . " = " .$value);
        $this->statement = $stmt; 
    }

    public function query() {
        if(!$this->statement->execute()){
            throw new Exception("There was an error with your query.");
            exit();
        }
        $this->log->info($this->query);
        if(substr($this->query,0 ,6) == "SELECT") return $this->getResults();
    }

    public function getResults() {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function setDatabase($name){
        $this->db_name = $name;
    }

    public function kill() {
        $this->db_conn = null;
        $this->statement = null;
    }

    protected function ValidateInfo(...$values) {
        foreach($values as $e){
            if(!is_string($e) || strlen($e) < 1){
                return false; 
            }
        }
        return true;
    }
}