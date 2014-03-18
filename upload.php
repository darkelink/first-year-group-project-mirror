<?php

require_once('constants.php');

$storeFolder = 'uploads';

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
  $size = $_FILES['file']['size'];
  $tempFile = $_FILES['file']['tmp_name'];
  $mimeType = getimagesize($tempFile)['mime'];

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

