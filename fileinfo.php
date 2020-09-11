<?php
if (!isset($_GET["relativeFilePath"]))
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

$fileInfo = array();
$fileInfo["url"] = "http://10.0.0.1/munins-archiv-file-service/fileInfo/".$_GET["relativePath"];
$fileInfo["size"] = filesize($rootPath.$_GET["relativePath"]);
$fileInfo["name"] = basename($rootPath.$_GET["relativePath"]);
$fileInfo["extension"] = pathinfo($rootPath.$_GET["relativePath"], PATHINFO_EXTENSION);
$fileInfo["creationDate"] = filectime($rootPath.$_GET["relativePath"]);
$fileInfo["lastModifiedDate"] = filemtime($rootPath.$_GET["relativePath"]);

echo json_encode($fileInfo); 
