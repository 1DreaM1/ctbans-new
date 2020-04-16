<?php

require_once "Database.php";

class Loader
{
    private $DB;

    public function __construct()
    {
        $this->DB = new Database();
    }

    public function getBansCount($filter = "") {
        $sql = "SELECT * FROM `ctbans` {$filter}";
        $result = $this->DB->dbCon->query($sql);
        return $result->num_rows;
    }

    public function getReceipts($filter = "") {
        $query = $this->DB->dbCon->query("SELECT * FROM `ctbans` {$filter} ORDER BY `id` DESC;");

        $data = array();
        $index = 0;
        while ($row = $query->fetch_assoc()) {
            $data[$index][0] = $row['id'];
            $data[$index][1] = $row['perp_steamid'];
            $data[$index][2] = $row['admin_steamid'];
            $data[$index][3] = $row['created'];
            $data[$index][4] = $row['length'];
            $data[$index][5] = $row['timeleft'];
            $data[$index][6] = $row['reason'];
            $data[$index][7] = $row['removed'];

            $index++;
        }
        return $data;
    }

    public static function createTable($data) {
        $table = "";
        foreach ($data as $item) {

            $color = "";
            if($item[7] == "E") {
                $color = "text-success";
                $item[5] = "Expired";
            }
            else if($item[7] == "R") {
                $color = "text-primary";
                $item[5] = "Unbanned";
            }
            else if($item[7] == "N") {
                $color = "text-warning";
            }

            $table .= "<tr data-toggle=\"collapse\" data-target=\"#collapse{$item[0]}\">
                      <td style=\"display: none;\" class='ban_id' data-id='{$item[0]}'>{$item[0]}</td>
                      <td>". self::getUserName(self::convertSteamIdToCommunityId($item[1])) ."</td>
                      <td>". self::getUserName(self::convertSteamIdToCommunityId($item[2])) ."</td>
                      <td>". date("d.m.Y H:i:s", $item[3]) ."</td>
                      <td>". self::decimal_to_time((double)$item[4]) ."</td>
                      <td class='{$color}'>". self::decimal_to_time((double)$item[5]) ."</td>
                      <td>{$item[6]}</td>
                    </tr>\n";

            $table .= "<tr id='collapse{$item[0]}' class='collapse' style='text-indent:20px'>
                      <td style=\"display: none;\">{$item[0]}</td>
                      <td><a href='http://steamcommunity.com/profiles/". self::convertSteamIdToCommunityId($item[1]) ."'>". $item[1] ."</a></td>
                      <td><a href='http://steamcommunity.com/profiles/". self::convertSteamIdToCommunityId($item[2]) ."'>". $item[2] ."</a></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>\n";
        }
        return $table;
    }

    public static function getUserName($steamid) {
        $xml = simplexml_load_file("http://steamcommunity.com/profiles/$steamid/?xml=1");
        if(!empty($xml))
            return $xml->steamID;
        else
            return "Nan";
    }

    public static function getUserAvatar($steamid) {
        $xml = simplexml_load_file("http://steamcommunity.com/profiles/$steamid/?xml=1");
        if(!empty($xml))
            return $xml->avatarIcon;
        else
            return "Nan";
    }

    public static function getUserInfo($steamid) {
        $data = array();
        $xml = simplexml_load_file("http://steamcommunity.com/profiles/$steamid/?xml=1");
        if(!empty($xml)) {
            $data[0] = $xml->stateMessage;
            $data[1] = $xml->memberSince;
            $data[2] = $xml->customURL;
            return $data;
        }
        else
            return $data;
    }

    public static function convertSteamIdToCommunityId($steamId) {
        if($steamId == 'STEAM_ID_LAN' || $steamId == 'BOT') {
            echo ("Cannot convert SteamID \"$steamId\" to a community ID.<br><br>");
        }
        if (preg_match('/^STEAM_[0-1]:[0-1]:[0-9]+$/', $steamId)) {
            $steamId = explode(':', substr($steamId, 8));
            $steamId = $steamId[0] + $steamId[1] * 2 + 1197960265728;
            return '7656' . $steamId;
        } elseif (preg_match('/^\[U:[0-1]:[0-9]+\]$/', $steamId)) {
            $steamId = explode(':', substr($steamId, 3, strlen($steamId) - 1));
            $steamId = $steamId[0] + $steamId[1] + 1197960265727;
            return '7656' . $steamId;
        } else {
            echo ("SteamID \"$steamId\" doesn't have the correct format.<br><br>");
        }
    }

    public static function decimal_to_time($time) {
        if(is_numeric($time)) {
            if ($time >= 60) {
                return round($time / 60, 1, PHP_ROUND_HALF_UP) . " hod";
            }
            else
                return $time . " min";
        } else
            return $time;
    }
}