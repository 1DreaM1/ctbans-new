<?php
require_once "Database.php";
require_once "SourceQuery/SourceQuery/bootstrap.php";

use xPaw\SourceQuery\SourceQuery;

class Server
{
    private $DB;
    private $SQ;

    public function __construct()
    {
        $this->DB = new Database();
        $this->SQ = new SourceQuery();
    }

    public function getServers($filter = NULL) {
        $query = $this->DB->dbCon->query("SELECT * FROM `ctbans_servers` {$filter}");

        $data = array();
        $index = 0;
        while ($row = $query->fetch_assoc()) {
            $data[$index][0] = $row['id'];
            $data[$index][1] = $row['ip'];
            $data[$index][2] = $row['port'];
            $data[$index][3] = self::simple_crypt($row['rcon'], 'd');
            $index++;
        }
        return $data;
    }

    public function connectServer($ip, $port) {
        $Timer = MicroTime(true);
        $Info    = Array();
        $Players = Array();
        $Error = "";

        try
        {
            $this->SQ->Connect($ip, $port, 6, SourceQuery::SOURCE);
            $Info    = $this->SQ->GetInfo();
            $Players = $this->SQ->GetPlayers();
        }
        catch(Exception $e)
        {
            $Error = $e->getMessage();
        }
        finally
        {
            $this->SQ->Disconnect();
        }

        $Timer = Number_Format(MicroTime( true ) - $Timer, 4, '.', '');

        return Array(
            "Error" => $Error,
            "Info" => $Info,
            "Player" => $Players,
            "Timer" => $Timer
        );
    }

    public function serverRcon($command, $ip, $port, $rcon)
    {
        $result = "";
        try
        {
            $this->SQ->Connect($ip, $port, 6, SourceQuery::SOURCE);
            $this->SQ->SetRconPassword($rcon);
            $result = $this->SQ->Rcon($command);
        }
        catch(Exception $e)
        {
            $result = $e->getMessage();
        }
        finally
        {
            $this->SQ->Disconnect();
        }

        return $result;
    }

    public function unbanPlayer($id)
    {
        $sql = "SELECT * FROM `ctbans` WHERE `id`='{$id}'";
        $result = $this->DB->dbCon->query($sql);
        $ban = $result->fetch_array();
        $sid = $ban["perp_steamid"];
        $result = "";

        foreach ($this->getServers() as $server) {
            $result .= $this->serverRcon("sm_unctban \"{$sid}\"", $server[1], $server[2], $server[3]);
            $result .= "\n";
        }

        return $result;
    }

    public function addServer($ip, $port, $rcon = "NULL")
    {
        $rcon = self::simple_crypt($rcon, 'e');
        $sql = "INSERT INTO `ctbans_servers`(`ip`, `port`, `rcon`) VALUES ('{$ip}','{$port}','{$rcon}')";
        return $this->DB->dbCon->query($sql);
    }

    public function dropServer($id)
    {
        $sql = "DELETE FROM `ctbans_servers` WHERE `id`='{$id}'";
        return $this->DB->dbCon->query($sql);
    }

    public function banPlayer($id, $serverID, $time, $reason)
    {
        $server = $this->getServers("WHERE `id`='{$serverID}'");
        return $this->serverRcon("sm_ctban \"{$id}\" {$time} \"{$reason}\"", $server[0][1], $server[0][2], $server[0][3]);
    }

    public function offlineBanPlayer($id, $time, $reason)
    {
        $server = $this->getServers();
        return $this->serverRcon("sm_offlinectban \"{$id}\" {$time} \"{$reason}\"", $server[0][1], $server[0][2], $server[0][3]);
    }

    public function kickPlayer($id, $serverID)
    {
        $server = $this->getServers("WHERE `id`='{$serverID}'");
        return $this->serverRcon("sm_kick \"{$id}\"", $server[0][1], $server[0][2], $server[0][3]);
    }

    public function createServerTable($data, $isAdmin = false) {
        $table = "";
        error_reporting(E_ERROR | E_PARSE);
        foreach ($data as $item) {
            $server = $this->connectServer($item[1], $item[2]);

            $actions = "";
            if($isAdmin)
                $actions = "<td class='text-center pt-1 pb-1'>
                            <a href=\"#\" class=\"btn btn-danger btn-circle btn-sm deleteServer\" data-id=\"{$item[0]}\"><i class=\"fas fa-trash-alt\"></i></a>
                          </td>";

            $table .= "<tr class='serverInfoModal'>
                      <td style=\"display: none;\" class='server_id' data-id='{$item[0]}'>{$item[0]}</td>
                      <td><img style='width:26px' src='img/". $server['Info']['ModDir'] .".png' alt='mod_img'/></td>
                      <td><img style='width:26px' src='img/". $server['Info']['Os'] .".png' alt='os_img'/></td>
                      <td><img style='width:26px' src='img/". $server['Info']['Secure'] .".png' alt='vac_img'/></td>
                      <td>". $server['Info']['HostName'] ."</td>
                      <td>". $server['Info']['Players'] ."/". $server['Info']['MaxPlayers'] ."</td>
                      <td>". $server['Info']['Map'] ."</td>
                      {$actions}
                    </tr>\n";
        }

        error_reporting(E_ALL);
        return $table;
    }

    public static function simple_crypt($string, $action = 'e') {
        if(!file_exists(__DIR__ . "/keys.ini")) {
            $keysFile = fopen(__DIR__ . "/keys.ini", "w");
            $keys = md5(uniqid(rand(), true)) . "\n";
            $keys .= md5(uniqid(rand(), true));
            fwrite($keysFile, $keys);
            fclose($keysFile);
        }

        $keys = fopen(__DIR__ . "/keys.ini","r");
        $secret_key = fgets($keys);
        $secret_iv = fgets($keys);
        fclose($keys);

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        }
        else if($action == 'd'){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}