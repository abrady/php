<html xmlns="http://www.w3.org/1999/xhtml">
	 <head>
	 <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	 <title>Test Page</title>
	 <meta name="Description" content="Test Page" />
	 <meta name="Keywords" content="Test,Page" />
	 </head>
	 <body>
	 <?php
	 ;
// comments 
$string = 'hello world!<br/>';
echo $string;
print $string;
printf('%s', $string);
print 'implode: ' . implode(",",$_REQUEST) . '<br/>';
print 'http_build_query: ' . http_build_query($_REQUEST)  . '<br/>';
?>
</body>
</html> 