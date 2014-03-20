<?php

require_once('constants.php');
require_once('config.inc.php');

$storeFolder = 'uploads';
$found = false;

if (isset($_GET['getFiles'])) { 
  // Opening the page
  $result  = [];
  $dir = dir($storeFolder);
  while (($file = $dir->read()) !== false) {
    if ($file != '.' && $file != '..') {
      $obj['name'] = $file;
      $obj['size'] = filesize($dir->path . DS . $file);
      // I still don't see how this is better than $result[] = $obj;
      array_push($result, array('name' => $obj['name'], 'size' => $obj['size']));
    }
  }
  header('Content-type: application/json');
  echo json_encode($result);
} else if (!empty($_FILES)) {
  // A file was uploaded

  // Get info about the file
  $ClientIP = $_SERVER['REMOTE_ADDR'];
  $size = $_FILES['file']['size'];
  $tempFile = $_FILES['file']['tmp_name'];
  $mimeType = getimagesize($tempFile)['mime'];
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

  // Check if valid file
  // dropzone already does these checks, but do them again to make sure
  if (in_array($mimeType, unserialize(IMAGE_FILE_TYPES))) {
    if ($size < MAX_FILE_SIZE) {
      $targetPath = dirname( __FILE__ ) . DS . $storeFolder . DS;

      // Scan the directory for the next missing file name
      $nextName = -1;
      $itr = 0;
      while ($nextName == -1) {
        if (!file_exists($targetPath . $itr++)) {
          $nextName = $itr - 1;
        }
      }
      // Move the uploaded file and use the empty file name that was found
      $targetFile =  $targetPath . $nextName; 
      move_uploaded_file($tempFile, $targetFile);

      // Insert or update into database table
      // 
      if ($b_stmt = $mysqli->prepare("SELECT 'ID' FROM 'plop_files'"))
      {
      	$b_stmt->execute();
      	$b_stmt->bind_result($dbID);
      	// If ID is in database:
      	//		1) Update the reports to 0 and the owner to the new ip
      	while(!$found && $b_stmt->fetch())
      	{
      		if ($dbID == $nextName)
      		{
      			$found = true;
      			$b_stmt->close();
      			if ($u_stmt = $mysqli->prepare("UPDATE 'plop_files' SET 'Reports number' = ?, 'Owner' = ? WHERE 'ID' = ?"))
      			{
	      			$tempZero = 0;
	      			$u_stmt->bind_param('iss', $tempZero, $ClientIP, $dbID);
	      			$u_stmt->execute();
	      			$u_stmt->close();
              $mysqli->close();
	      		} //if
      		} //if
      	} //while
      } //if

      	// If ID is not in database:
      	//		1) Insert ID,Owner, and reports equal to 0
    	if(!$found)
    	{
    		$b_stmt->close();
    		if ($i_stmt = $mysqli->prepare("INSERT INTO 'plop_files'('ID', 'Reports number','Owner') VALUES (?,?,?)"))
    		{
    			$tempZero = 0;
    			$i_stmt->bind_param('sis', $nextName, $tempZero, $ClientIP);
    			$i_stmt->execute();
    			$i_stmt->close();
          $mysqli->close();
    		} //if
    	} //if
    
      // Check we the max number of files hasn't been reached
      if ($itr == MAX_FILES) {
        $itr = 0;
      }
      // Delete the next file, leaving a new space
      unlink($targetPath . $itr);
    } else {
      // File is too big
      // there is no standart HTTP error code for this, so we can use anything
      header('HTTP/1.1 418 I\'m a teapot');
      echo 'Image is too big';
    }
  } else {
    // File is not a known image
    header('HTTP/1.1 415 Unsupported Media Type');
    echo 'Image type not recognised';
  }
} else {
  // no data was recieved
  header('HTTP/1.1 500 Internal Server Error');
  echo 'Error: No files were not uploaded';
}
?>

