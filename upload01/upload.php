<?php

//The files have a link on a page for downloading
//and filenames are also put in the progress bar so
//the file can be viewed in the browser (ie. PDF files)
//so replace a few characters.  Since the file links are
//loaded onto another page via php and filenames
//are displayed, I wanted to use this method instead
//of url_encode() [just looks funny when displayed]

//print "ufiles: " . implode(",",$_FILES['ufile']);
//print '<br/>post files: ' . $_FILES['ufile']['tmp_name'] . ' path: ' .  $path;

// 'ufile' is the name of one of the posted arguments (see form.html)
// $_FILES is hashed by argument name
// $_FILES['ufile'] has the following fields: name, type, size, tmp_name (this is the name of the local file, i.e. what was copied to the server), error.
// uploading tmp.txt successfully gave:
// foo.txt, text/plain, 10, C:\UniServer\tmp\php7239.tmp,0 (for success)

// see
// http://www.php.net/manual/en/features.file-upload.post-method.php


$SafeFile = $_FILES['ufile']['name'];
$SafeFile = str_replace("#", "No.", $SafeFile);
$SafeFile = str_replace("$", "Dollar", $SafeFile);
$SafeFile = str_replace("%", "Percent", $SafeFile);
$SafeFile = str_replace("^", "", $SafeFile);
$SafeFile = str_replace("&", "and", $SafeFile);
$SafeFile = str_replace("*", "", $SafeFile);
$SafeFile = str_replace("?", "", $SafeFile);

$uploaddir = "uploads/";
$path = $uploaddir.$SafeFile;


if($ufile != none){ //AS LONG AS A FILE WAS SELECTED...

    if(copy($_FILES['ufile']['tmp_name'], $path)){ //IF IT HAS BEEN COPIED..

        //GET FILE NAME
        $theFileName = $_FILES['ufile']['name'];

        //GET FILE SIZE
        $theFileSize = $_FILES['ufile']['size'];

        if ($theFileSize>999999){ //IF GREATER THAN 999KB, DISPLAY AS MB
            $theDiv = $theFileSize / 1000000;
            $theFileSize = round($theDiv, 1)." MB"; //round($WhatToRound, $DecimalPlaces)
        } else { //OTHERWISE DISPLAY AS KB
            $theDiv = $theFileSize / 1000;
            $theFileSize = round($theDiv, 1)." KB"; //round($WhatToRound, $DecimalPlaces)
        }

echo <<<UPLS
<table cellpadding="5" width="300">
<tr>
    <td align="Center" colspan="2"><font color="#009900"><b>Upload Successful</b></font></td>
</tr>
<tr>
    <td align="right"><b>File Name: </b></td>
    <td align="left">$theFileName</td>
</tr>
<tr>
    <td align="right"><b>File Size: </b></td>
    <td align="left">$theFileSize</td>
</tr>
<tr>
    <td align="right"><b>Directory: </b></td>
    <td align="left">$uploaddir</td>
</tr>
</table>

UPLS;

    } else {

//PRINT AN ERROR IF THE FILE COULD NOT BE COPIED
echo <<<UPLF
<table cellpadding="5" width="80%">
<tr>
<td align="Center" colspan="2"><font color=\"#C80000\"><b>File could not be uploaded</b></font></td>
</tr>

</table>

UPLF;
    }
}

?>