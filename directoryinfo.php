<?php
if (!isset($_GET["relativePath"]))
{
	http_response_code(404);
	return;
}

$rootPath = "/srv/samba/AAF/Fundstellen/Akten/";

if (!file_exists($rootPath.$_GET["relativePath"])) 
{
	http_response_code(404);
	return;
}

$dirInfo = array();
$dirInfo["relativePath"] = $_GET["relativePath"];
$dirInfo["creationDate"] = filectime($rootPath.$_GET["relativePath"]);
$dirInfo["lastModifiedDate"] = filemtime($rootPath.$_GET["relativePath"]);

echo json_encode($dirInfo);
