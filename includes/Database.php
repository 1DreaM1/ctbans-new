<?php

class Database
{
    const DB_HOSTNAME = '--';
    const DB_USERNAME = '--';
    const DB_PASSWORD = '--';
    const DB_NAME = '--';

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
            return $query = $this->dbCon->multi_query($sql);
        }
    }

    public function __destruct(){
        $this->dbCon->close();
    }
}