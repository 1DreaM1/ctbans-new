<?php
require_once "../Loader.php";

$loader = new Loader();

if(!isset($_POST['id']) || !isset($_POST['action'])) {
    exit();
}

$id = stripcslashes($_POST['id']);
$action = stripcslashes($_POST['action']);

switch ($action) {
    case "unban":
        $result = $loader->unbanPlayer($id);
        echo $result;
        break;

    case "delete":
        $result = $loader->deleteBan($id);
        echo $result;
        break;
}