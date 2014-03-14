<?php

require_once('config.inc.php');
require_once('constants.php');

$storeFolder = 'uploads';

if (!empty($_FILES)) {
  // A file was uploaded

  // Get info about the file
  $size = $_FILES['file']['size'];
  $tempFile = $_FILES['file']['tmp_name'];
  $mimeType = getimagesize($tempFile)['mime'];

  // Check if valid file TODO:send error if not
  if (in_array($mimeType, array_merge(unserialize(IMAGE_FILE_TYPES))) && $size < MAX_FILE_SIZE) {
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
  
    // Check we the max number of files hasn't been reached
    if ($itr == MAX_FILES) {
      $itr = 0;
    }
    // Delete the next file, leaving a new space
    unlink($targetPath . $itr);
  }
} else { 
  // Opening the page
  $result  = [];
  $dir = dir($storeFolder);
  while (($file = $dir->read()) != false) {
    if ($file != '.' && $file != '..') {
      $obj['name'] = $file;
      $obj['size'] = filesize($dir->path . DS . $file);
      // I still don't see how this is better than $result[] = $obj;
      array_push($result, array('name' => $obj['name'], 'size' => $obj['size']));
    }
  }
  header('Content-type: application/json');
  echo json_encode($result);
}
?>

