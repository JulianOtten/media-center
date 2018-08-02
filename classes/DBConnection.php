<?php

class DBConnection {
    public $db_host;
    public $db_user;
    private $db_password;
    public $db_name;
    private $db_conn;
    private $statement;
    private $query;

    public function __construct($host, $user, $password, $name) {
        if(!$this->ValidateInfo($host, $user, $name)){
            throw new Exception("One of the giving arguments isn't a string or has no content...");
        }
        $this->db_host = $host;
        $this->db_user = $user;
        $this->db_password = $password;
        $this->db_name = $name;
    }

    public function connect() {
        try {
            $db_conn = new PDO("mysql:dbname=$this->db_name;host=$this->db_host", $this->db_user, $this->db_password);
            $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db_conn = $db_conn;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function prepare($query) {
        $conn = $this->db_conn;
        $this->query = $query;
        $stmt = $conn->prepare($query);
        $this->statement = $stmt;
    }

    public function bindParam($param, $value) {
        $stmt = $this->statement;
        $stmt->bindParam($param, $value);
        $this->statement = $stmt; 
    }

    public function query() {
        $this->logQuery($this->query);
        if(!$this->statement->execute()){
            throw new Exception("There was an error with your query.");
            exit();
        }
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

    protected function logQuery($query){
        $logs = __DIR__ . "/../logs/";
        $date = date('d-m-Y') . ".log";
        file_put_contents(
            $logs . $date,
            sprintf("[%s] %s\n", date("Y-m-d h:i:s"), $query),
            FILE_APPEND
        );
    }
}