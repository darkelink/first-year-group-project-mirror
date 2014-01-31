<?php

require_once('databaseHandler.php');

if ($_FILES)
{
  /*
    -to determine the type of file for the moment, we will use getimagesize()
     however when support for other files is added, something different will 
     have to be done (maybe the getID3 library?)
   */

  //checkFileType();
  //checkFileSize();

  //find appropriate ID
  $oldestFileID = getOldestID();
 
  $name = $_FILES['filename']['name'];

  $size = getimagesize($name);
  $image_type = $size['mime'];


  //should use getLatestID() butcannot tell what it is doing

  if($oldestFileID == 19)
    $newestFileID = 0;
  else
    $newestFileID = $oldestFileID + 1;

  $DBconnection = DBConnect();


  $result = $DBconnection -> prepare("UPDATE file_data SET file-data = " . file_get_contents($name) . "file-type = $image_type WHERE ID = $newestFileID");

//  $result -> bind_param("i", $newestFileID);
//  $result -> execute();


  // upload(getLatestID)
  // UPDATE query
  DBdisconnect($DBconnection);
  



}
?>
