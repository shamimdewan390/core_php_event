<?php


require_once '../../classes/Event.php';
require_once __DIR__ . '/../../config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!$user_id){
    header("Location: " . $base_url . "index.php");
}
$id = $_GET['id'];
$objEvent = new Event();
$result = $objEvent->delete('events', ['id'=>$id]);



if($result){
    header("Location: " . $base_url . "/views/event/index.php");
}

