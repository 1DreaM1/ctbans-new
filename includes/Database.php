<?php

class Database
{
    const DB_HOSTNAME = 'localhost';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = 'ctbans';

    public $dbCon;

    public function __construct(){
        $this->dbCon = new mysqli(self::DB_HOSTNAME,self::DB_USERNAME,self::DB_PASSWORD,self::DB_NAME);

        $this->dbCon->set_charset("utf8");

        if ($this->dbCon->connect_errno) {
            die("Connect failed: ". $this->dbCon->connect_error);
        }

        $this->createDatabase();
    }

    public function createDatabase() {
        $query = $this->dbCon->query("DESCRIBE `ctbans_users`");
        if(!$query) {
            $sql = file_get_contents(__DIR__ . "/sql/ctbans_users.sql");
            $this->dbCon->multi_query($sql);
        }

        $query = $this->dbCon->query("DESCRIBE `ctbans_servers`");
        if(!$query) {
            $sql = file_get_contents(__DIR__ . "/sql/ctbans_servers.sql");
            $this->dbCon->multi_query($sql);
        }
    }

    public function __destruct(){
        $this->dbCon->close();
    }
}