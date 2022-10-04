<?php

namespace Erp360\Core\Services;

use Erp360\Core\Helpers\DatabaseAdapter;

class BaseService extends DatabaseAdapter {
    
    protected $database;
    
    public function __construct()
    {
        parent::__construct();
        $this->database = $this->conn;
    }

}