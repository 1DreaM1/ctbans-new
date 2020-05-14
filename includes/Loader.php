<?php
require_once "Database.php";

class Loader
{
    private $DB;

    public function __construct()
    {
        $this->DB = new Database();
    }

    public function getBansCount($filter = NULL)
    {
        $sql = "SELECT * FROM `ctbans` {$filter}";
        $result = $this->DB->dbCon->query($sql);
        return $result->num_rows;
    }

    public function getBans($filter = NULL, $limit = NULL)
    {
        $query = $this->DB->dbCon->query("SELECT * FROM `ctbans` {$filter} ORDER BY `id` DESC {$limit};");

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

    public function unbanPlayer($id)
    {
        $sql = "UPDATE `ctbans` SET `timeleft`=-1, `removed`='R' WHERE `id`='{$id}'";
        return $this->DB->dbCon->query($sql);
    }

    public function deleteBan($id)
    {
        $sql = "DELETE FROM `ctbans` WHERE `id`='{$id}'";
        return $this->DB->dbCon->query($sql);
    }

    public static function createTable($data, $isAdmin = false)
    {
        $table = "";
        foreach ($data as $item) {
            if ($item[7] == "E") {
                $item[5] = "<a href=\"#\" class=\"badge badge-success\">Expired</a>";
            } else if ($item[4] == "0" || $item[4] == 0) {
                $item[5] = "<a href=\"#\" class=\"badge badge-danger\">Permanent</a>";
                $item[4] = "<a href=\"#\" class=\"badge badge-danger\">Permanent</a>";
            } else if ($item[7] == "R") {
                $item[5] = "<a href=\"#\" class=\"badge badge-primary\">Unbanned</a>";
            } else if ($item[7] == "N") {
                $item[5] = "<a href=\"#\" class=\"badge badge-warning\">". self::decimal_to_time((double)$item[5]) ."</a>";
            }

            $actions = "";
            if($isAdmin && $item[7] == "N")
                $actions = "<td class='text-center pt-1 pb-1'>
                            <a href=\"#\" class=\"btn btn-primary btn-circle btn-sm mr-1 unban\" data-id=\"{$item[0]}\"><i class=\"fas fa-unlock-alt\"></i></a>
                            <a href=\"#\" class=\"btn btn-danger btn-circle btn-sm delete\" data-id=\"{$item[0]}\"><i class=\"fas fa-trash-alt\"></i></a>
                          </td>";
            elseif ($isAdmin && ($item[7] == "E" || $item[7] == "R"))
                $actions = "<td class='text-center pt-1 pb-1'>
                                <a href=\"#\" style='cursor:no-drop;' class=\"btn btn-secondary btn-circle btn-sm mr-1\" data-id=\"{$item[0]}\"><i class=\"fas fa-unlock-alt\"></i></a>
                                <a href=\"#\" class=\"btn btn-danger btn-circle btn-sm delete\" data-id=\"{$item[0]}\"><i class=\"fas fa-trash-alt\"></i></a>
                            </td>";

            $table .= "<tr class='infoModal'>
                      <td style=\"display: none;\" class='ban_id' data-id='{$item[0]}'>{$item[0]}</td>
                      <td>" . self::getUserName(self::convertSteamIdToCommunityId($item[1])) . "</td>
                      <td>" . date("d.m.Y H:i:s", $item[3]) . "</td>
                      <td>" . self::decimal_to_time($item[4]) . "</td>
                      <td><strong>{$item[6]}</strong></td>
                      <td>{$item[5]}</td>
                      {$actions}
                    </tr>\n";
        }
        return $table;
    }

    public static function getUserName($steam64)
    {
        $xml = simplexml_load_file("http://steamcommunity.com/profiles/$steam64/?xml=1","SimpleXMLElement",LIBXML_NOCDATA);
        if (!empty($xml))
            return $xml->steamID;
        else
            return "Nan";
    }

    public static function getUserAvatar($steamid)
    {
        $xml = simplexml_load_file("http://steamcommunity.com/profiles/$steamid/?xml=1","SimpleXMLElement",LIBXML_NOCDATA);
        if (!empty($xml))
            return $xml->avatarIcon;
        else
            return "Nan";
    }

    public static function getUserInfo($steamid)
    {
        $data = array();
        $xml = simplexml_load_file("http://steamcommunity.com/profiles/$steamid/?xml=1","SimpleXMLElement",LIBXML_NOCDATA);
        if (!empty($xml)) {
            $data[0] = $xml->stateMessage;
            $data[1] = $xml->memberSince;
            $data[2] = $xml->customURL;
            return $data;
        } else
            return $data;
    }

    public static function convertSteamIdToCommunityId($steamId)
    {
        if ($steamId == 'STEAM_ID_LAN' || $steamId == 'BOT') {
            echo("Cannot convert SteamID \"$steamId\" to a community ID.<br><br>");
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
            echo("SteamID \"$steamId\" doesn't have the correct format.<br><br>");
        }
    }

    public static function toSteamID($id) {
        if (is_numeric($id) && strlen($id) >= 16) {
            $z = bcdiv(bcsub($id, '76561197960265728'), '2');
        } elseif (is_numeric($id)) {
            $z = bcdiv($id, '2'); // Actually new User ID format
        } else {
            return $id; // We have no idea what this is, so just return it.
        }
        $y = bcmod($id, '2');
        return 'STEAM_0:' . $y . ':' . floor($z);
    }

    public static function decimal_to_time($time)
    {
        if (is_numeric($time)) {
            if ($time > (60 * 24))
                return round($time / (60 * 24), 1, PHP_ROUND_HALF_UP) . " days";
            else if ($time >= 60) {
                return round($time / 60, 1, PHP_ROUND_HALF_UP) . " hod";
            } else
                return $time . " min";
        } else
            return $time;
    }
}