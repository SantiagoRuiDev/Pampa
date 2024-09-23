<?php

// @ Copy and paste this base in new Models.
namespace App\Model;

use Module\Model;

class TestModel extends Model
{
    function get(){
        $query = $this->statement()->prepare('SELECT * FROM tablename');

        $rows = $query->fetchAll($query->execute());

        return $rows;
    }
}