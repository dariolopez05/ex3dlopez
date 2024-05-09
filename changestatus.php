<?php

session_start();
require_once "autoloader.php";

$id=$_GET['id'];
$status=$_GET['status'];
$data = new Lighting;

$data->changeState($id, $status);
header("location: index.php");
?>