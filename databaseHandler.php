<?php

require_once('config.inc.php');

// Constants
define('MAX_FILES', '20');

function getOldestID()
{
  // this will only get initalised once
  static $oldestFileID = 0;

  // reset the pointer if it is at the end of the database
  $oldestFileID++;
  if ($oldestFileID >= MAX_FILES)
    $oldestFileID = 0;
  return $oldestFileID;
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

/*
   Moved to processFile.php

function checkFileType()
{
}

function checkFileSize()
{
}
*/

/*

DEPRECATED: Use getOldestID insted

function getLatestID()
{
  if ($oldestFileID == 19) {
    $oldestFileID = 0;
  } else {
    $oldestFileID++;
  }
  return $oldestFileID;
}
*/

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

    $res = $result->get_result();
    $row = $res->fetch_assoc();
    
    return $row['file_data'];
  }
  DBdisconnect($DBconnection);
}

function uploadFile( /* temp */ )
{
  //this has been moved to a separate script
}

?>