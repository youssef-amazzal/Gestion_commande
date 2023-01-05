<?php

class DBConnection
{
    // Hold the class instance.
    private static $instance = null;

    // The database connection.
    private PDO $conn;

    // The connection parameters
    private $HOST = 'localhost';
    private $USERNAME = 'root';
    private $PASSWORD = '';
    private $DATABASE = 'gestion_commande';

    // The constructor is private to prevent direct creation of object.
    private function __construct()
    {
        $this->conn = new PDO("mysql:host={$this->HOST};dbname={$this->DATABASE}", $this->USERNAME, $this->PASSWORD);
    }

    // The object is created from within the class itself only if the class has no instance.
    public static function getInstance() : static
    {
        if (self::$instance == null)
        {
            self::$instance = new DBConnection();
        }

        return self::$instance;
    }

    // Get the database connection.
    public function getConnection() : PDO
    {
        return $this->conn;
    }
}

