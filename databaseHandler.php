<?php

require_once('config.inc.php');


$oldestFileID = 0;

function setOldestID()
{
  //this is necessary because if a method is called directly the above line 
  //will not be executed. May want to remove the line, but have kept for
  //compatibility reasons
  //////////WILL WANT TO MODIFY TO STORE THE ID AND GET THE STORED ID///////
  $oldestFileID = 0;
}

function DBConnect()
{
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

  if($mysqli -> connect_error) 
  {
    die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }
  return $mysqli;
}

function DBdisconnect(mysqli $connection)
{
  $connection->close();
}

function checkFileType()
{
}

function checkFileSize()
{
}

function getLatestID()
{
  if ($oldestFileID == 19) {
    $oldestFileID = 0;
  } else {
    $oldestFileID++;
  }
  return $oldestFileID;
}

/////////////////////////////////////////////////
// public
/////////////////////////////////////////////////
function getAllFiles()
{
}

function getFile(int $id)
{
  $DBconnection = DBConnect();
  
  if ($result = $DBConnection -> prepare("SELECT file_data FROM plop_files WHERE ID=?")) {
    $result->bind_param("i", $id);
    $result->execute();
    //TODO: return
  }
  DBdisconnect($DBconnection);
}

function uploadFile( /* temp */ )
{
  //this has been moved to a separate script
}

?>
