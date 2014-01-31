<?php

require_once('config.inc.php');

$oldestFileID = 0;

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
  /*
    -to determine the type of file for the moment, we will use getimagesize()
     however when support for other files is added, something different will 
     have to be done (maybe the getID3 library?)
   */

  //checkFileType();
  //checkFileSize();

  //find appropriate ID
  //should use getLatestID() butcannot tell what it is doing
  if($oldestFileID == 19)
    $newestFileID = 0;
  else
    $newestFileID = $oldestFileID + 1;
  
  $DBconnection = DBConnect();
  
  $result = $DBconnection -> prepare("UPDATE file_data SET file-data = " . file_get_contents($_POST) /*the file*/. "file-type = /*the file type */ WHERE ID = ?");
  $result -> bind_param;
  $result -> execute();

  // upload(getLatestID)
  // UPDATE query
  DBdisconnect($DBconnection);

}

?>
