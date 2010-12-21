<?php
//$LF = "<br/>";
$LF = "\n";

require 'db.php';

// print the uploaded information
//print "files:\r\n";
//print_r($_FILES); // <= assoc array by form id (i.e. photo) and then an array of file info
//print "posted info:\r\n";
//print_r($_POST); // <= other posted info: image_name, image_type

$num_files = $_POST['num_files'];
print 'num files: ' . $num_files . $LF;

function process_file($name, $tmp_fname)
{
	$LF = $GLOBALS['LF'];
	print "adding $name, $tmp_fname" . $LF;
	
//	if (!preg_match("/(gif|jpg|jpeg|png)$/",$name)) {
//		print $upload_file . " : doesn't appear to be an image" . $LF;
//		return;
//	}

	if (!is_uploaded_file($tmp_fname))  {
		print "file " . $tmp_fname . " wasn't a file that was uploaded" . $LF;
		return;
	} 

	if (!db_add($name, $tmp_fname)) {
		print "couldn't add file " . $name . $LF;
		return;
	}

	print "added $name to db successfully" . $LF;
}

for($i = 0; $i < $num_files; $i++){
	$key = 'file' . $i;
	process_file($_FILES[$key]['name'],$_FILES[$key]['tmp_name']);
}

?>