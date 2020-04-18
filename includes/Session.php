<?php
require_once "Loader.php";
require_once "Database.php";

class Session
{
    private $DB;

    public function __construct()
    {
        $this->DB = new Database();
    }

    public function checkUser($user) {
        $error = "";

        if($this->checkFirstUser()) {
            $this->registerUser($user);
            return $error;
        }

        $user = stripslashes($user);
        $user = $this->DB->dbCon->real_escape_string($user);

        $query_r = $this->DB->dbCon->query("SELECT * FROM `ctbans_users` WHERE `user`='$user' LIMIT 1");
        $rows = $query_r->num_rows;
        $row = $query_r->fetch_array();

        if ($rows != 1) {
            $error = "ERROR 505";
        }

        return $error;
    }

    private function checkFirstUser() {
        $query = $this->DB->dbCon->query("SELECT * FROM `ctbans_users`");
        if($query->num_rows == 0)
            return true;
        else
            return false;
    }

    public function registerUser($user) {
        $date = date("d.m.Y");
        $query = $this->DB->dbCon->query("INSERT INTO `ctbans_users`(`user`, `date`) VALUES ('{$user}','{$date}')");
        if($query)
            return true;
        else
            return false;
    }

    public function usersCount($filter = NULL) {
        $sql = "SELECT * FROM `ctbans_users` {$filter}";
        $result = $this->DB->dbCon->query($sql);
        return $result->num_rows;
    }

    public function createUsersTable() {
        $query = $this->DB->dbCon->query("SELECT * FROM `ctbans_users`");

        $table = "";
        while ($row = $query->fetch_assoc()) {

            $checkbox = "";
            if($row['id'] != 0) {
                $checkbox = "<div class=\"custom-control custom-checkbox\">
                              <input type=\"checkbox\" class=\"custom-control-input usercheck\" data-id=\"{$row['id']}\" id=\"userCheck{$row['id']}\">
                              <label class=\"custom-control-label\" for=\"userCheck{$row['id']}\" style=\"cursor:pointer;\"></label>
                            </div>";
            }

            $table .= "<tr>
                          <td scope=\"row\">
                            {$checkbox}
                          </td>
                          <td>". Loader::getUserName($row['user']) ."</td>
                          <td>{$row['user']}</td>
                          <td>{$row['date']}</td>
                      </tr>\n";
        }
        return $table;
    }

    public function dropUser($steamid) {
        $sql = "DELETE FROM `ctbans_users` WHERE `user`='{$steamid}'";
        return $this->DB->dbCon->query($sql);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM `ctbans_users` WHERE `id`='{$id}'";
        return $this->DB->dbCon->query($sql);
    }
}