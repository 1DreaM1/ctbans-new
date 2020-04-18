<?php
require_once "../Loader.php";

$loader = new Loader();

if(!isset($_GET['id'])) {
    exit();
}

$id = stripcslashes($_GET['id']);
$ban = $loader->getBans("WHERE `id`='{$id}'");

$data = array(
    "admin" => Loader::getUserName(Loader::convertSteamIdToCommunityId($ban[0][2])),
    "admin_sid" => $ban[0][2],
    "admin_link" => "http://steamcommunity.com/profiles/" . Loader::convertSteamIdToCommunityId($ban[0][2]),
    "perp" => Loader::getUserName(Loader::convertSteamIdToCommunityId($ban[0][1])),
    "perp_sid" => $ban[0][1],
    "perp_link" => "http://steamcommunity.com/profiles/" . Loader::convertSteamIdToCommunityId($ban[0][1])
);
print_r(json_encode($data));