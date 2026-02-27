<?php
/*
 * Base Model 
 * Every model will extend this to get access to the database wrapper
 */
class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }
}
