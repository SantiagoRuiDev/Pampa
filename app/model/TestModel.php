<?php

// @ Copy and paste this base in new Models.
namespace App\Model;

use App\Database\Database;
use PDO;

class TestModel
{
    private PDO $PDO;

    public function __construct()
    {
        $initDatabase = new Database();
        $this->PDO = $initDatabase->connect();
    }
    // @ BASE //

    // @ Example of handler for Database Query
    public function getTest(): array | bool
    {
        $statement = $this->PDO->prepare('SELECT * FROM test');

        $query = $statement->fetchAll($statement->execute());

        return $query;
    }
}
