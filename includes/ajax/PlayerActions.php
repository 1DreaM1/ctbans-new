<?php
require_once "../Loader.php";
require_once "../Server.php";

$loader = new Loader();
$server = new Server();

if(!isset($_POST['id']) || !isset($_POST['action'])) {
    exit();
}

$serverID = isset($_POST['serverid'])? stripcslashes($_POST['serverid']) : NULL;
$time = isset($_POST['time'])? stripcslashes($_POST['time']) : NULL;
$reason = isset($_POST['reason'])? stripcslashes($_POST['reason']) : NULL;

$id = stripcslashes($_POST['id']);
$action = stripcslashes($_POST['action']);

switch ($action) {
    case "unban":
        $result = $server->unbanPlayer($id);
        echo $result;
        break;

    case "ban":
        $result = $server->banPlayer($id, $serverID, $time, $reason);
        echo $result;
        break;

    case "kick":
        $result = $server->kickPlayer($id, $serverID);
        echo $result;
        break;

    case "delete":
        $result = $loader->deleteBan($id);
        echo $result;
        break;

    case "offline_ban":
        $result = $server->offlineBanPlayer($id, $time, $reason);
        echo $result;
        break;
}