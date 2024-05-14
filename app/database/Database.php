<?php

namespace App\Database;

require 'vendor/autoload.php';

use PDO;
use PDOException;

class Database
{
    // Connection details PDO
    private string $host = "localhost";
    private string $dbname = "test";
    private string $user = "root";
    private string $password = "";

    /**
     * Establish a connection with the database.
     *
     * @return PDO
     */
    public function connect(): PDO | string
    {
        try {
            // Intentamos la conexion a la db.
            $PDO = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->user, $this->password);
            return $PDO; // La retornamos a quien haya llamado al metodo.
        } catch (PDOException $e) {
            return $e->getMessage(); // Si hay un error devolvemos el mensaje para mejur debug.
        }
    }
}
