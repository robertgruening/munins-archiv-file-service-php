<?php
error_reporting(E_ALL);
//ini_set("display_errors", 1);

include_once(__DIR__."/logger.php");
include_once(__DIR__."/config.php");

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    Get();
}
else if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    Post();
}
else {
	global $logger;
	$logger->error("Unsupported HTTP method!");
}

function Get()
{
	global $logger;
	$logger->info("Get()");
	
	if (!isset($_GET["relativePath"]))
	{
		$logger->error("Parameter 'relativePath' is not set!");
		http_response_code(404);
		return;
	}
	
	$logger->debug("relativePath=".$_GET["relativePath"]);

	if (!file_exists(ROOT_DIR.$_GET["relativePath"])) 
	{
		$logger->error("File '".ROOT_DIR.$_GET["relativePath"]."' not found!");
		http_response_code(404);
		return;
	}

	header("content-type: ".mime_content_type(pathinfo(ROOT_DIR.$_GET["relativePath"], PATHINFO_FILENAME)));
	readfile(ROOT_DIR.$_GET["relativePath"]);
	return;
}

function Post()
{
	global $logger;
	$logger->info("Post()");
	
	if (!isset($_GET["relativePath"]))
	{
		$logger->error("Parameter 'relativePath' is not set!");
		http_response_code(404);
		return;
	}
	
	$logger->debug("relativePath=".$_GET["relativePath"]);

	if (!file_exists(ROOT_DIR.$_GET["relativePath"])) 
	{
		$logger->debug("File '".ROOT_DIR.$_GET["relativePath"]."' not found!");
	}
	
	$file = $_FILES["file"];
	$logger->debug("name=".$file["name"]);
	$logger->debug("tmp_name=".$file["tmp_name"]);
	$logger->debug("size=".$file["size"]);
	$logger->debug("error=".$file["error"]);
	$logger->debug("extension=".strtolower(pathinfo($file["name"], PATHINFO_EXTENSION)));
	
	$logger->debug("tmp_path=".$file["tmp_name"]."/".$file["name"]);
	
	$logger->debug(move_uploaded_file($file["tmp_name"], "./tmp/".$file["name"]));
	
	$root_path = "Fundstellen\Akten";
	$kontext_path = $_GET["relativePath"];
	
	// Ordner anlegen
	$kontext_path_segments = explode("/", $kontext_path);
	$directory = "\\";
	
	if (isset($kontext_path_segments) &&
		count($kontext_path_segments) >= 1) {

		for ($i = 0; $i < count($kontext_path_segments); $i++) {
		
			if (($i + 1) == count($kontext_path_segments)) {
				break;
			}
			
			$directory .= $kontext_path_segments[$i]."\\";
			$commandCreateDirectoryInSmbShare = "smbclient -U aaf //server/AAF -N -c 'mkdir ".$root_path.$directory.";';";	
			$logger->debug("command=".$commandCreateDirectoryInSmbShare);
			$commandResult = shell_exec($commandCreateDirectoryInSmbShare);
			$logger->debug("command result=".$commandResult);			
		}
	}
	
	// Datei hochladen
	$commandUploadFileToSmbShare = "cd ./tmp/;smbclient -U aaf //server/AAF -N -c 'cd ".$root_path.$_GET["relativePath"]."; put ".$file["name"].";';";
	$logger->debug("command=".$commandUploadFileToSmbShare);
	$commandResult = shell_exec($commandUploadFileToSmbShare);
	$logger->debug("command result=".$commandResult);
	
	unlink("./tmp/".$file["name"]);
	
	if (file_exists("./tmp/".$file["name"])) {
		$logger->warn("./tmp/".$file["name"]." not deleted");
	}
	else {
		$logger->debug("./tmp/".$file["name"]." deleted");
	}
	
	return;
}
