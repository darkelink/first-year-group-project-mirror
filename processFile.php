<?php

// Constants
// Not sure if this will work, need to test soon
define ("IMAGE_FILE_TYPES", serialize(array("image/jpeg", "image/gif", "image/png")));

// TODO: Confirm this value
define ("MAX_FILE_SIZE", '20000000'); // 20mb

function getFileType(string $file)
{
  $finfo = finfo_open(FILEINFO_MIME, '/usr/share/mime/magic' );
  if (!$finfo)
    $finfo = finfo_open(FILEINFO_MIME, '/usr/share/file/magic' );
  if (!$finfo)
    $finfo = finfo_open(FILEINFO_MIME, '/usr/share/misc/magic' );
  if (!$finfo)
    return "magic database not found";

  $mimeType = finfo_file($finfo, $file);
  finfo_close($finfo);

  return $mimeType;
}

function isAllowedFile(string $mimeType)
{
  // Might need to do some other checks here
  return in_array($mimeType, array_merge(unserialize(IMAGE_FILE_TYPES)));
}

function getFileSize(string $file)
{
  return filesize($file);
}

function checkFile(string $file)
{
  return isAllowedFile(getFileType($file)) && getFileSize($file) < MAX_FILE_SIZE;
}

?>
