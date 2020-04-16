<?php
require_once "../Session.php";

$session = new Session();

if(!isset($_POST['data']) && !isset($_POST['sid'])) {
    echo "User not selected !";
    exit();
}

$IDs = isset($_POST['data'])? $_POST['data'] : "" ;
$action = $_POST['action'];
$sid = isset($_POST['sid'])? $_POST['sid'] : "";

try {
    switch($action) {
        case 'add':
            $session->registerUser($sid);
            break;

        case 'delete':
            foreach ($IDs as $id) {
                $session->deleteUser($id);
            }
            break;
    }
    echo "SUCCESS";
} catch (Exception $e) {
    echo $e->getMessage();
}