<?php
	$reail_filename = $_GET["name"];
	$file_dir = $_GET["url"];
	//$file_dir = str_replace("/outer","",getcwd()) . $file_dir;
	
	header('Content-Type: application/x-octetstream');
	header('Content-Length: '.filesize($file_dir));
	header('Content-Disposition: attachment; filename='.$reail_filename);
	header('Content-Transfer-Encoding: binary');
	
	$fp = fopen($file_dir, "r");
	fpassthru($fp);
	fclose($fp);

?>