<?php

require_once('config.inc.php');


$ds = DIRECTORY_SEPARATOR;
$storeFolder = 'uploads';

if (!empty($_FILES)) {
  // A file was uploaded
  $tempFile = $_FILES['file']['tmp_name'];
  $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
  $targetFile =  $targetPath. $_FILES['file']['name']; 
  move_uploaded_file($tempFile,$targetFile);
    
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);
  if($mysqli -> connect_error) {
    die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  } else {
    // For some reason, this always returns false
    if ($stmt = $mysqli->prepare("INSERT INTO test (`temp`) VALUES (:test)")) {
      $stmt->bind_param(':test', "GTRJSO"); 
      $stmt->execute();
      printf("%d Row inserted.\n", $stmt->affected_rows);
      $stmt->close();
    } else {
      // TODO: temp hack as above always fails for some reason
      // this does not protect against any attacks
      mysqli_query($mysqli,"INSERT INTO test (`temp`) VALUES (". mysqli_real_escape_string($mysqli, $targetPath) .")") or die(mysqli_error($mysqli));
    }
  }
  // This can take a very long time for some reason
  $mysqli->close();
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