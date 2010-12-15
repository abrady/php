<?php

// print the uploaded information
print_r($_FILES); // <= assoc array by form id (i.e. photo) and then an array of file info
print_r($_POST); // <= other posted info: image_name, image_type
$upload_dir = 'uploads/';

$upload_file = $upload_dir . basename($_FILES['file0']['name']);
print $upload_file . "\r\n";


if (!preg_match("/(gif|jpg|jpeg|png)$/",$upload_file) ){
	print $upload_file . " : doesn't appear to be an image";
} else {
	if (!is_uploaded_file($_FILES['file0']['tmp_name']))  {
		print "file " . $upload_file . " wasn't a file that was uploaded";
	} else if (!move_uploaded_file($_FILES['file0']['tmp_name'], $upload_file)) {
		print "couldn't move file " . $_FILES['file0']['tmp_name'] . " to " . $upload_file;
	}
	else {
		print "success!";
	}
}




















