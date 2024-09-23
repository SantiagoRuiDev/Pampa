<?php

// @ Copy and paste this base in new Models.
namespace Module;

use App\Database\Database;
use PDO;

/**
 * Abstract Model Class
 *
 * This class serves as the base model for all database interactions.
 * It initializes a PDO connection using the Database class and provides
 * a method to retrieve this connection for executing queries in child models.
 * Extend this class in other models to inherit database functionality.
 */
abstract class Model
{
    private PDO $PDO;

    public function __construct()
    {
        $initDatabase = new Database();
        $this->PDO = $initDatabase->connect();
    }

    /**
     * Retrieve PDO Connection
     *
     * This method returns the initialized PDO connection,
     * allowing other models that extend this class to perform
     * database queries using the established connection.
     *
     * @return PDO The active PDO connection instance.
     */
    protected function statement(): PDO {
        return $this->PDO;
    }
}
