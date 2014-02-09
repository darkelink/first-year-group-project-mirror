<?php
// To do :
/* 	-> Connect to database
		-> Store file path(includes file name)/file name(same as id)/file type
				at latest id in database (id = row number)
		-> Maybe make sure we can link user to file in database
		-> Close connection
		///
		-> Change processFile.php so it changes requirements based on type
*/

// Optimisation is going to be needed
// List issues here:
// -> decreaseOldestID() can be avoided by moving id increase inside if

//-----------------------------------------------------------------------------
// Declare other files required - start

// Needed to connect and deal with the database
require_once('databaseHandler.php');

// Needed to validate file type and size
require_once('processFile.php');

// Declare other files required - end
//-----------------------------------------------------------------------------
// Declare variables - start

// Increments the ID and returns the id under which the file is going to be saved
$oldest_file_id = getOldestID();

// This needs to be set correctly
// Sets up the folder to which the files are going to be uploaded
$upload_directory = 'uploads/';

// This is used in conjunction with move_uploaded_file
// Basename() adds the actual file name to the name we want to store
$upload_file = $upload_directory . $oldest_file_id; //. basename($_FILES[file][name]);

// Stores the temporary name assigned by the upload process
$file_name = $_FILES['file']['tmp_name'];


// Don't know if redundant ----------------------------------------------------
// Stores the file size
// Getimagesize limits upload type to images
$file_size = $_FILES['file']['size'] / (1024 * 1024);

// Stores the file type (string after the dot in the name)
// This is not a very good way of checking the file type as it is done client-side
$file_type = $_FILES['file']['type'];
// $file_type = $file_size['mime']; // What is this ? - Catalin

// Declare variables - end
//-----------------------------------------------------------------------------


// Size should change depending on file type
// To change accepted file types go to processFile.php

// Check for errors
if (!$_FILES['file']['error'])
{
	// Check size and type
	if (checkFile($file_name))
	{
		// Check if valid file
		if (move_uploaded_file($file_name, $upload_file))
		{
				echo "File successfully uploaded </br>";
		}

		else 
		{
			echo "Error : Something is not right </br>";
			decreaseOldestID();
		}
	}
	else 
	{
		echo "Error : File is too big and/or is the wrong type </br>";
		decreaseOldestID();
	}
}
else 
{
	echo "Error : " . $_FILES['file']['error'] . "</br>";
	decreaseOldestID();
}


/*
$DBconnection = DBConnect();

$result = $DBconnection -> prepare("UPDATE file_data SET file-data = " . file_get_contents($name) . "file-type = $image_type WHERE ID = $newestFileID");

//  $result -> bind_param("i", $newestFileID);
//  $result -> execute();

// upload(getLatestID)
// UPDATE query

DBdisconnect($DBconnection);  
*/

?>
