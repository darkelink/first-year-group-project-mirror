<?php

require_once("config.inc.php");

$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);
if ($stmt = $mysqli->prepare("TRUNCATE TABLE `IP_Addresses`"))
  $stmt->execute();

?>