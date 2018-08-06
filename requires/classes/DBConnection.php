<?php
require_once 'Logger.php';

class DBConnection {
    private $db_host;
    private $db_user;
    private $db_password;
    private $db_name;
    private $db_conn;
    private $statement;
    private $log;
    private $query;
    /**
     * Creates a PDO connection with the given parameters
     * 
     * @param String $host The name of the host
     * @param String $user the name of the user
     * @param String $password the password for the user account
     * @param String $name the name of the database you want to connect to
     */
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
            echo ('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Prepares a query for you to use with the PDO object
     * Make sure the given query is using propper PDO syntax
     * So use :param rather than hardcoding the param in the query
     * 
     * @param String $query the query you want to execute
     */
    public function prepare($query) {
        $this->query = $query;
        $conn = $this->db_conn;
        $stmt = $conn->prepare($query);
        $this->statement = $stmt;
    }

    /**
     * Binds a param to the given query just like PDO
     * Also logs the value of $param to the logger file
     * 
     * @param String $param the parameter thats in the query, example :param
     * @param String|Int $value the value you want to replace the param with
     * @param PDO:PARAM|null $type The type of the variable, example: PDO:PARAM_STR
     */
    public function bindParam($param, $value, $type) {
        if(!isset($this->statement)) throw new Exception("No statement prepared! Please prepare a query before binding parameters!");
        $stmt = $this->statement;
        if(isset($type)) $stmt->bindParam($param, $value, $type);
        else $stmt->bindParam($param, $value);
        $this->log->info($param . " = " .$value);
        $this->statement = $stmt; 
    }

    /**
     * Execute the query you asigned
     * Also logs the query to the logger file
     * 
     * @return Array the ASSOC array that comes with a select statement
     */
    public function query() {
        if(!$this->statement->execute()){
            throw new Exception("There was an error with your query.");
            exit();
        }
        $this->log->info($this->query);
        if(substr($this->query,0 ,6) == "SELECT") return $this->getResults();
    }

    private function getResults() {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * kills the current connection with the database
     * 
     * @return void
     */
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