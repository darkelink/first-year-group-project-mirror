<?php

require_once("config.inc.php");
require_once("constants.php");

$ClientIP = $_SERVER['REMOTE_ADDR'];
$ClientProxy = $_SERVER['HTTP_X_FORWARDED_FOR'];
$file_id = $_POST['file']['name']
$found = false;

$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

// Check Database for Client IP and Client Proxy
if($stmt = $mysqli->prepare("SELECT `Client IP`, `Client Proxy` FROM `IP_Addresses`"))
{
  $stmt->execute();
  $stmt->bind_result($ip, $proxy);
  while($stmt->fetch())
  {
    // CHECK LIST FOR IP
    if($ip == $ClientIP && !$found)
    {
      $found = true;
      if($r_stmt = $mysqli->prepare("SELECT `Reports` FROM `IP_Addresses` WHERE `Client IP` = ?"))
      {
        $r_stmt->bind_param('s', $ip)
        $r_stmt->execute();
        $r_stmt->bind_result($report_number);
        $r_stmt->fetch()
        if($report_number < MAX_REPORTS)
        {
          if($u_stmt = $mysqli->prepare("UPDATE `IP_Addresses` SET `Reports`= ? WHERE `Client IP` = ?"));
          {
            $u_stmt->bind_param('is', $report_number + 1, $ip);
            $u_stmt->execute();
            $u_stmt->close();
          }
          if($u_stmt = $mysqli->prepare("SELECT `Reports` FROM `plop_files` WHERE `ID`= ?"))
          {
            $u_stmt->bind_param('i', $file_id);
            $u_stmt->execute();
            $u_stmt->bind_result($times_reported);
            $u_stmt->close();
          }
          if($u_stmt = $mysqli->prepare("UPDATE `plop_files` SET `Reports`= ? WHERE `ID`= ?"))
          {
            $u_stmt->bind_param('ii', $times_reported, $file_id);
            $u_stmt->execute();
            $u_stmt->close();
          }
        }
      }
    }
    // CHECK LIST FOR PROXY
    elseif($proxy == $ClientProxy && !$found)
    {
      $found = true;
      if($r_stmt = $mysqli->prepare("SELECT `Reports` FROM `IP_Addresses` WHERE `Client Proxy` = ?"))
      {
        $r_stmt->bind_param('s', $proxy)
        $r_stmt->execute();
        $r_stmt->bind_result($report_number);
        $r_stmt->fetch()
        if($report_number < MAX_REPORTS)
        {
          if($u_stmt = $mysqli->prepare("UPDATE `IP_Addresses` SET `Reports`= ? WHERE `Client Proxy` = ?"));
          {
            $u_stmt->bind_param('is', $report_number + 1, $ip);
            $u_stmt->execute();
            $u_stmt->close();
          }
          if($u_stmt = $mysqli->prepare("SELECT `Reports` FROM `plop_files` WHERE `ID`= ?"))
          {
            $u_stmt->bind_param('i', $file_id);
            $u_stmt->execute();
            $u_stmt->bind_result($times_reported);
            $u_stmt->close();
          }
          if($u_stmt = $mysqli->prepare("UPDATE `plop_files` SET `Reports`= ? WHERE `ID`= ?"))
          {
            $u_stmt->bind_param('ii', $times_reported, $file_id);
            $u_stmt->execute();
            $u_stmt->close();
          }
        }
      }
    }
  }
  $stmt->close();

  // Create entry for IP and Proxy
  if (!$found)
  {
    if($stmt = $mysqli->prepare("INSERT INTO `IP_Addresses`(`Client IP`, `Client Proxy`, `Reports`) VALUES (?,?,?)"))
    {
      $stmt->bind_param('ssi', $ClientIP, $ClientProxy, 1);
      $stmt->execute();
      $stmt->close();
    }
    if($stmt = $mysqli->prepare("SELECT `Reports` FROM `plop_files` WHERE `ID`= ?"))
    {
      $stmt->bind_param('i', $file_id);
      $stmt->execute();
      $stmt->bind_result($times_reported);
      $stmt->close();
    }
    if($u_stmt = $mysqli->prepare("UPDATE `plop_files` SET `Reports`= ? WHERE `ID`= ?"))
    {
      $stmt->bind_param('ii', $times_reported, $file_id);
      $stmt->execute();
      $stmt->close();
    }
  }
  $mysqli->close();
}


?>