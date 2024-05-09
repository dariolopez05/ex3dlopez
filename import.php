<?php

session_start();
require_once "autoloader.php";

$data = new Lighting;

$result = $data->importLamps('lighting.csv');

?>