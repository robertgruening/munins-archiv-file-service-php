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

if (!is_dir(ROOT_DIR.$_GET["relativePath"])) 
{
	http_response_code(404);
	return;
}

$dirInfo = array();
$dirInfo["relativePath"] = $_GET["relativePath"];
$dirInfo["creationDate"] = filectime(ROOT_DIR.$_GET["relativePath"]);
$dirInfo["lastModifiedDate"] = filemtime(ROOT_DIR.$_GET["relativePath"]);

echo json_encode($dirInfo);
