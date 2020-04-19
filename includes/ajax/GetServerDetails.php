<?php
require_once "../Server.php";
require_once "../SteamAuth/SteamAuth.class.php";

$auth = new SteamAuth();
$auth->Init();

$server = new Server();

if(!isset($_GET['id'])) {
    exit();
}

$id = stripcslashes($_GET['id']);
$serverData = $server->getServers("WHERE `id`='{$id}'");
$info = $server->connectServer($serverData[0][1], $serverData[0][2]);

$table = "";
foreach ($info["Player"] as $player) {
    $actions = "";
    if($auth->IsUserLoggedIn())
        $actions = "<td class='text-center pt-1 pb-1'>
                        <a href=\"#\" class=\"btn btn-primary btn-circle btn-sm mr-1 ban\" data-id=\"{$player['Name']}\"><i class=\"fas fa-user-lock\"></i></a>
                        <a href=\"#\" class=\"btn btn-warning btn-circle btn-sm kick\" data-id=\"{$player['Name']}\"><i class=\"fas fa-user-slash\"></i></a>
                      </td>";

    $table .= "<tr>
              <td style=\"display: none;\" class='server_id' data-id=''></td>
              <td>". $player['Name'] ."</td>
              <td>". $player['Frags'] ."</td>
              <td>". $player['TimeF'] ."</td>
              {$actions}
            </tr>\n";
}

print $table;