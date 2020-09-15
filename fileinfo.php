<?php
include_once(__DIR__."/config.php");

if (!isset($_GET["relativePath"]))
{
	http_response_code(404);
	return;
}

if (!file_exists(ROOT_DIR.$_GET["relativePath"])) 
{
	http_response_code(404);
	return;
}

if (!is_file(ROOT_DIR.$_GET["relativePath"])) 
{
	http_response_code(404);
	return;
}

$fileInfo = array();
$fileInfo["relativePath"] = $_GET["relativePath"];
$fileInfo["size"] = filesize(ROOT_DIR.$_GET["relativePath"]);
$fileInfo["name"] = basename(ROOT_DIR.$_GET["relativePath"]);
$fileInfo["extension"] = pathinfo(ROOT_DIR.$_GET["relativePath"], PATHINFO_EXTENSION);
$fileInfo["creationDate"] = filectime(ROOT_DIR.$_GET["relativePath"]);
$fileInfo["lastModifiedDate"] = filemtime(ROOT_DIR.$_GET["relativePath"]);

echo json_encode($fileInfo); 
