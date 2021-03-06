<?php

//$LF = "\n";
$LF = "<br/>";

// print out the rows from a query NOTE: destructive

function _mysql_print_error($err_msg)
{
	print $err_msg . " sql error:{" . mysql_error() . "}". $GLOBALS['LF'];
}


function _mysql_table_exists($table_name) 
{
	$q = mysql_query("SHOW TABLES LIKE '$table_name'");
	if(!$q)
		return false;
	return mysql_num_rows($q) == 1;
}

function _mysql_show_columns($table_name)
{
	print "columns for table: ";
	$q = mysql_query("SHOW COLUMNS FROM $table_name");
	if(!$q){
		_mysql_print_error("couldn't query table $table_name");
		return;
	}
	while($row = mysql_fetch_assoc($q))
		print_r($row);

	mysql_free_result($q); // not strictly necessary but saves memory.
	
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
}


try {
	
	
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
		_mysql_print_error("failed to connect to mysql_connect($host, $user, $pass);");
		return;
	}

	
//print_r($conn);
// print $LF;
//print basename(getcwd());
	
	$db_name = "db_".basename(getcwd());
	// print "db_name: $db_name" . $LF;

	// remember, SQL is case IN-sensitive
	// $db_found = false;
	// $db_list = mysql_list_dbs();
	// while ($row = mysql_fetch_object($db_list)) {
	// 	print "database: ";
	// 	print_r($row);
	// 	print $LF;
	// 	if(0==strcasecmp($row->Database, $db_name)){
	// 		print "db found! ". $LF;
	// 		$db_found = true;
	// 		break;
	// 	}	
	// }
	
	if(!mysql_select_db($db_name)){
		print "couldn't select db $db_name, creating" . $LF;

		if(!mysql_query("CREATE DATABASE $db_name"))
		{
			_mysql_print_error("couldn't create db $db_name");
			return;
		}
		print "db created" . $LF;
	}

	if(!mysql_select_db($db_name)){
		_mysql_print_error("couldn't use db $db_name even after creating it");
		return;
	}
	print "using db $db_name" . $LF;

	//TODO make sure the table matches up
	if(!_mysql_table_exists("test")){
		print "table 'test' doesn't exist. making" . $LF;
		$q = mysql_query("CREATE TABLE test (" 
						 . "id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT"
						 . ", name VARCHAR(32)"
						 . ", pic BLOB"
						 . ", PRIMARY KEY (id))");
		if(!$q){
			print "couldn't create table 'test'" . $LF;
			return;
		}
		else{
			print "create succeeded table: ";
			print_r($q);
			print $LF;
		}
	}

	print "table 'test' exists" . $LF;
	
//	_mysql_show_columns('test');
	
	// insert the data
	// make sure to use 'mysql_real_escape_string()' for binary data
	$raw_data = "--\r\n'\"--";
	$data = mysql_real_escape_string($raw_data);
	print $data . $LF;
	$q = mysql_query("INSERT INTO test (name, pic) VALUES ('foo', '{$data}')");
	if(!$q){
		_mysql_print_error("insert of data failed.");
		return;
	}

	print "done!" . $LF;
}
catch(Exception $e) {
	echo 'caught exception: ', $e->getMessage(), $LF;
}



















