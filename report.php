<?php

require_once("config.inc.php");
require_once("constants.php");

$ClientIP = $_SERVER['REMOTE_ADDR'];
$file_id = $_GET['name'];
$array = explode(DS, $file_id);
$file_id = end($array);
$found = false;
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);


// Check Database for Client IP and Client Proxy
if($stmt = $mysqli->prepare("SELECT `Client IP` FROM `IP_Addresses`"))
{
  $stmt->execute();
  $stmt->bind_result($ip);
  while(!$found && $stmt->fetch())
  {
    // CHECK LIST FOR IP
    // If found set flag and :
    // 		1) Get number of reports so far
    //		2) Update the number of reports if they do not already exceed the limit
    //		3) Get the number of file reports
    //		4) Update the number of file reports
    if($ip == $ClientIP)
    {
      $found = true;
      $stmt->close();
      if($r_stmt = $mysqli->prepare("SELECT `Reports` FROM `IP_Addresses` WHERE `Client IP` = ?"))
      {
        $r_stmt->bind_param('s', $ip);
        $r_stmt->execute();
        $r_stmt->bind_result($report_number);
        $r_stmt->fetch();
        $r_stmt->close();
        if($report_number < MAX_REPORTS)
        {
        	if($u_stmt = $mysqli->prepare("SELECT `IP of Reports` FROM `plop_files` WHERE ID = ?"))
        	{
        		$u_stmt->bind_param('s', $file_id);
        		$u_stmt->execute();
        		$u_stmt->bind_result($ipReportList)
        		$u_stmt->fetch();
        		$u_stmt->close();
        	}
        	$ipArray = explode(" ", $ipReportList);
        	if (!in_array($ip, $ipArray)
        	{
	          if($u_stmt = $mysqli->prepare("UPDATE `IP_Addresses` SET `Reports`= ? WHERE `Client IP` = ?"))
	          {
	            $nextReport = $report_number + 1;
	            $u_stmt->bind_param('is', $nextReport, $ip);
	            $u_stmt->execute();
		          $u_stmt->close();
	          }
	          if($u_stmt = $mysqli->prepare("SELECT `Reports` FROM `plop_files` WHERE `ID`= ?"))
	          {
	            $u_stmt->bind_param('s', $file_id);
	            $u_stmt->execute();
	            $u_stmt->bind_result($times_reported);
	            $u_stmt->fetch();
		          $u_stmt->close();
	          }
	          if($u_stmt = $mysqli->prepare("UPDATE `plop_files` SET `Reports`= ? WHERE `ID`= ?"))
	          {
	            $u_stmt->bind_param('is', $times_reported, $file_id);
	            $u_stmt->execute();
	            $u_stmt->close();
	          }
          	if($u_stmt = $mysqli->prepare("UPDATE `plop_files` SET `IP of Reports` = ? WHERE `ID`= ?"))
          	{
	          	$ipReportList .= " " . $ip;
	          	$u_stmt->bind_param('ss', $ipReportList, $file_id);
	          	$u_stmt->execute();
	          	$u_stmt->close();
         	 	}
         	} //if
        } //if
      } //if
    } //if
  } //while

  // If found flag not set:
  //		1) Make new ip entry
  //		2) Get the file report number
  //		3) Update file report number
  if (!$found)
  {
    $stmt->close();
    if($u_stmt = $mysqli->prepare("INSERT INTO `IP_Addresses`(`Client IP`, `Reports`) VALUES (?,?)"))
    {
      $tempOne = 1;
      $u_stmt->bind_param('si', $ClientIP, $tempOne);
      $u_stmt->execute();
      $u_stmt->close();
    }
    if($u_stmt = $mysqli->prepare("SELECT `Reports` FROM `plop_files` WHERE `ID`= ?"))
    {
      $u_stmt->bind_param('s', $file_id);
      $u_stmt->execute();
      $u_stmt->bind_result($times_reported);
      $u_stmt->fetch();
      $u_stmt->close();
    }
    if($u_stmt = $mysqli->prepare("UPDATE `plop_files` SET `Reports`= ? WHERE `ID`= ?"))
    {
      $u_stmt->bind_param('is', $times_reported, $file_id);
      $u_stmt->execute();
      $u_stmt->close();
    }
  }
}


if ($mysqli != null)
{
  $mysqli->close();
}

?>
