<?php
/*
   Hold all the constants in one place
*/

// Supported file types
define ("IMAGE_FILE_TYPES", serialize(array("image/jpeg", "image/gif", "image/png")));


// TODO: seperate max file size based on file type
// TODO: decide on these values
// Maxium file sizes
define ("MAX_FILE_SIZE", '20000000'); // 20mb

// TODO: decide on a value for this
define ('MAX_FILES', '50');

// Maximum number of reports per ip per 30 or 60 minutes
define ('MAX_REPORTS', '5');

define ('DS', DIRECTORY_SEPARATOR);
?>
