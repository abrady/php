<?php

// print the uploaded information
print "files:\r\n";
print_r($_FILES); // <= assoc array by form id (i.e. photo) and then an array of file info
print "posted info:\r\n";
print_r($_POST); // <= other posted info: image_name, image_type

$num_files = $_POST['num_files'];
print 'num files: ' . $num_files . "\r\n";

function process_file($name, $tmp_fname)
{	
	$upload_dir = 'uploads/';
	$upload_file = $upload_dir . basename($name);
	print $upload_file . "\r\n";

	if (!preg_match("/(gif|jpg|jpeg|png)$/",$name)) {
		print $upload_file . " : doesn't appear to be an image";
	} else {
		if (!is_uploaded_file($tmp_fname))  {
			print "file " . $upload_file . " wasn't a file that was uploaded";
		} else if (!move_uploaded_file($tmp_fname, $upload_file)) {
			print "couldn't move file " . $upload_file;
		}
		else {
			print "moved $tmp_fname to $upload_file \r\n";
		}
	}
}

for($i = 0; $i < $num_files; $i++){
	$key = 'file' . $i;
	process_file($_FILES[$key]['name'],$_FILES[$key]['tmp_name']);
}
