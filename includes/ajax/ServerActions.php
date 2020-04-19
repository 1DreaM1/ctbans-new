<?php
require_once "../Server.php";

$server = new Server();

if(!isset($_POST['action'])) {
    exit();
}

$action = stripcslashes($_POST['action']);

$ip = isset($_POST['ip'])? stripcslashes($_POST['ip']) : NULL;
$port = isset($_POST['port'])? stripcslashes($_POST['port']) : NULL;
$rcon = isset($_POST['rcon'])? stripcslashes($_POST['rcon']) : NULL;
$id = isset($_POST['id'])? stripcslashes($_POST['id']) : NULL;


switch ($action) {
    case "add":
        $result = $server->addServer($ip, $port, $rcon);
        echo $result;
        break;

    case "delete":
        $result = $server->dropServer($id);
        echo $result;
        break;
}