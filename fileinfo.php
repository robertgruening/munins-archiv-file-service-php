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
