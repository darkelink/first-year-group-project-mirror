<?php

require_once("config.inc.php");
require_once("constants.php");

$ClientIP = $_SERVER['REMOTE_ADDR'];
$file_id = $_POST['file']['name'];
$found = false;

$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

// Check Database for Client IP and Client Proxy
echo "This always works\n";
if($stmt = $mysqli->prepare("SELECT `Client IP` FROM `IP_Addresses`"))
{
  echo "After first stmt\n";
  $stmt->execute();
  $stmt->bind_result($ip);
  echo "$ClientIP\n";
  while($stmt->fetch())
  {
    // CHECK LIST FOR IP
    echo "Test 1 - before ip check\n";
    echo "$found\n";
    echo "IP in datab : $ip\n";
    if($ip == $ClientIP && !$found)
    {
      $found = true;
      echo "Test 2 - after ip check\n";
  $stmt->close();
      if($r_stmt = $mysqli->prepare("SELECT `Reports` FROM `IP_Addresses` WHERE `Client IP` = ?"))
      {
        echo "First if test\n";
        $r_stmt->bind_param('s', $ip);
        $r_stmt->execute();
        $r_stmt->bind_result($report_number);
        echo "Test report number before fetch : $report_number\n";
        $r_stmt->fetch();
        echo "Test report number : $report_number\n";
        if($report_number < MAX_REPORTS)
        {
          if($u_stmt = $mysqli->prepare("UPDATE `IP_Addresses` SET `Reports`= ? WHERE `Client IP` = ?"))
          {
            echo "Check if update clause is good\n";
            $u_stmt->bind_param('is', ($report_number + 1), $ip);
            $u_stmt->execute();
          }
          if($u_stmt = $mysqli->prepare("SELECT `Reports` FROM `plop_files` WHERE `ID`= ?"))
          {
            $u_stmt->bind_param('s', $file_id);
            $u_stmt->execute();
            $u_stmt->bind_result($times_reported);
            $u_stmt->fetch();
          }
          if($u_stmt = $mysqli->prepare("UPDATE `plop_files` SET `Reports`= ? WHERE `ID`= ?"))
          {
            $u_stmt->bind_param('is', $times_reported, $file_id);
            $u_stmt->execute();
          }
          if ($u_stmt != null)
          {
            $u_stmt->close();
          }
        }
        $r_stmt->close();
      } else {
        echo $mysqli->error . '\n';
      }
    }
  }
    // Create entry for IP and Proxy
  echo "Test before !found if\n";
  if (!$found)
  {
    echo "Test inside !found if\n";
    if($u_stmt = $mysqli->prepare("INSERT INTO `IP_Addresses`(`Client IP`, `Reports`) VALUES (?,?)"))
    {
      $temp = 1;
      $u_stmt->bind_param('si', $ClientIP, $temp);
      $u_stmt->execute();
    }
    if($u_stmt = $mysqli->prepare("SELECT `Reports` FROM `plop_files` WHERE `ID`= ?"))
    {
      $u_stmt->bind_param('s', $file_id);
      $u_stmt->execute();
      $u_stmt->bind_result($times_reported);
      $u_stmt->fetch();
    }
    if($u_stmt = $mysqli->prepare("UPDATE `plop_files` SET `Reports`= ? WHERE `ID`= ?"))
    {
      $u_stmt->bind_param('is', $times_reported, $file_id);
      $u_stmt->execute();
    }
    if ($u_stmt != null)
    {
      $u_stmt->close();
    }
  }
}
if ($mysqli != null)
{
  $mysqli->close();
}

?>
