<?php
if (!isset($_GET["relativeFilePath"]))
{
	http_response_code(404);
	return;
}

$rootPath = "/srv/samba/AAF/Fundstellen/Akten/";

if (!file_exists($rootPath.$_GET["relativeFilePath"])) 
{
	http_response_code(404);
	return;
}

$fileInfo = array();
$fileInfo["url"] = "http://10.0.0.1/munins-archiv-file-service/fileInfo/".$_GET["relativeFilePath"];
$fileInfo["size"] = filesize($rootPath.$_GET["relativeFilePath"]);
$fileInfo["name"] = basename($rootPath.$_GET["relativeFilePath"]);
$fileInfo["extension"] = pathinfo($rootPath.$_GET["relativeFilePath"], PATHINFO_EXTENSION);
$fileInfo["creationDate"] = filectime($rootPath.$_GET["relativeFilePath"]);
$fileInfo["lastModifiedDate"] = filemtime($rootPath.$_GET["relativeFilePath"]);

echo json_encode($fileInfo); 
