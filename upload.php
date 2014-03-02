<?php

require_once('config.inc.php');
require_once('constants.php');

$ds = DIRECTORY_SEPARATOR;
$storeFolder = 'uploads';

if (!empty($_FILES)) {
  // A file was uploaded

  $size = $_FILES['file']['size'];
  $tempFile = $_FILES['file']['tmp_name'];
  $mimeType = getimagesize($tempFile)['mime'];

  if (in_array($mimeType, array_merge(unserialize(IMAGE_FILE_TYPES))) && $size < MAX_FILE_SIZE) {
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
    $targetFile =  $targetPath. $_FILES['file']['name']; 
    move_uploaded_file($tempFile,$targetFile);
  }
} else { 
  // Opening the page
  $result  = array();
  // Change to query database
  $files = scandir($storeFolder);
  if (false!==$files) {
    foreach ( $files as $file ) {
      if ( '.'!=$file && '..'!=$file) {
        $obj['name'] = $file;
        $obj['size'] = filesize($storeFolder.$ds.$file);
        $result[] = $obj;
      }
    }
  }
  header('Content-type: text/json');
  header('Content-type: application/json');
  echo json_encode($result);
}
?> 
