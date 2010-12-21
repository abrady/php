<?php

if(!defined('LF')){
	$LF = "\n";
}


// print out the rows from a query NOTE: destructive

function db_print_error($err_msg)
{
	print $err_msg . " sql error:{" . mysql_error() . "}". $GLOBALS['LF'];
}


function db_table_exists($table_name) 
{
	$q = mysql_query("SHOW TABLES LIKE '$table_name'");
	if(!$q)
		return false;
	return mysql_num_rows($q) == 1;
}

// what this returns:
// columns for table: Array
// (
//     [Field] => id
//     [Type] => smallint(5) unsigned
//     [Null] => NO
//     [Key] => PRI
//     [Default] =>
//     [Extra] => auto_increment
// )
// Array
// (
//     [Field] => name
//     [Type] => varchar(32)
//     [Null] => YES
//     [Key] =>
//     [Default] =>
//     [Extra] =>
// )
//
// mysql command line equivalent:
// +-------+----------------------+------+-----+---------+----------------+
// | Field | Type                 | Null | Key | Default | Extra          |
// +-------+----------------------+------+-----+---------+----------------+
// | id    | smallint(5) unsigned | NO   | PRI | NULL    | auto_increment |
// | name  | varchar(32)          | YES  |     | NULL    |                |
// +-------+----------------------+------+-----+---------+----------------+
function db_show_columns($table_name)
{
	print "columns for table: ";
	$q = mysql_query("SHOW COLUMNS FROM $table_name");
	if(!$q){
		db_print_error("couldn't query table $table_name");
		return;
	}
	while($row = mysql_fetch_assoc($q))
		print_r($row);

	mysql_free_result($q); // not strictly necessary but saves memory.	
}


/* these are the default host/user/password. if no params are
   passed to mysql_connect then these will be used:
   
   print 'default_host: ' . ini_get("mysql.default_host") . $LF;
   print 'default_user: ' . ini_get("mysql.default_user") . $LF;
   print 'default_pass: ' . ini_get("mysql.default_password") . $LF;
*/
$host = 'localhost';
$user = 'root';
$pass = 'root';
$conn = mysql_connect($host, $user, $pass);
	
if(!$conn) {
	db_print_error("failed to connect to mysql_connect($host, $user, $pass);");
	return;
}
	
$db_name = "db_".basename(getcwd());

if(!mysql_select_db($db_name)){
	print "couldn't select db $db_name, creating" . $LF;
	
	if(!mysql_query("CREATE DATABASE $db_name"))
	{
		db_print_error("couldn't create db $db_name");
		return;
	}
	print "db created" . $LF;
}

if(!mysql_select_db($db_name)){
	db_print_error("couldn't use db $db_name even after creating it");
	return;
}

// print "using db $db_name" . $LF;

	//TODO make sure the table matches up
$table = "test";

if(!db_table_exists("$table")){
	print "table '$table' doesn't exist. making" . $LF;
	$q = mysql_query("CREATE TABLE $table (" 
					 . "id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT"
					 . ", name VARCHAR(32)"
					 . ", pic BLOB"
					 . ", PRIMARY KEY (id))");
	if(!$q){
		print "couldn't create table '$table'" . $LF;
		return;
	}
	else{
		print "create succeeded table: ";
		print_r($q);
		print $LF;
	}
}

function db_add($name, $pic_fname)
{
	global $table;
					  
	$raw_data = file_get_contents($pic_fname);
	$data = mysql_real_escape_string($raw_data);
	$qs = "INSERT INTO $table (name, pic) VALUES ('foo', '{$data}')";
//	print $qs . "\n";
//	print $data . "<br/>\n";
	$q = mysql_query($qs);
	if(!$q){
		db_print_error("insert of data failed.");
		return false;
	}
	$num_inserts = mysql_affected_rows();
	$row_id = mysql_insert_id();
	print "inserted $num_inserts rows. id $row_id <br/>\n";
	//print_r();
	return $row_id;
}



?>