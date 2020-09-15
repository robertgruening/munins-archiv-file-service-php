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

header(mime_content_type(pathinfo(ROOT_DIR.$_GET["relativePath"], PATHINFO_FILENAME)));
readfile(ROOT_DIR.$_GET["relativePath"]);
