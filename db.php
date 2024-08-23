<?php

use PDO;
use PDOException;

class db
{

    public $host = 'xxxxx';
    public $dbname = 'xxxxxx';
    public $username = 'xxxxx'; // Reemplaza con tu usuario de MySQL
    public $password = 'xxxxx';
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
