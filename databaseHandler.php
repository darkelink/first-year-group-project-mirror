<?php
// To do :
/*	-> Change getFile function
    ///
	-> Write getAllFiles accordingly
	-> Additional functions ???
*/
//-----------------------------------------------------------------------------
// Declare other files required - start

require_once('config.inc.php');

// Declare other files required - end
//-----------------------------------------------------------------------------
// Declare variables - start

// Need to make sure this doesn't get incremented when no file is uploaded
static $oldestFileID = 0;

// Constants
define('MAX_FILES', '20');

// Declare variables - end
//-----------------------------------------------------------------------------

// Reset the pointer if it is at the end of the database
function getOldestID()
{
  $oldestFileID++;
  if ($oldestFileID >= MAX_FILES)
    $oldestFileID = 0;
  return $oldestFileID;
}

// Decrease the pointer if the upload failed
function decreaseOldestID()
{
	if ($oldestFileID == 0)
		$oldestFileID = MAX_FILES - 1;
	else
		$oldestFileID--;
	return $oldestFileID;
}

// Used to establish connection to database
function DBConnect()
{
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

  if($mysqli -> connect_error) 
  {
    die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }
  return $mysqli;
}

// Used to close established connection to database
function DBdisconnect(mysqli $connection)
{
  $connection->close();
}

///////////////////////////////////////////////////////////////////////////////
// public
///////////////////////////////////////////////////////////////////////////////

// WRITE HERE -> Don't know what is should do yet
// Write fuction to return all files uploaded by a certain user ?
function getAllFiles()
{}

// CHANGE HERE -> Don't know what it should do yet
// Change so that this returns a file uploaded by a specifi user ?
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

?>