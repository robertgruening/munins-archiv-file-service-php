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
$dirInfo["creationDate"] = date("c", filectime(ROOT_DIR.$_GET["relativePath"]));
$dirInfo["lastModifiedDate"] = date("c", filemtime(ROOT_DIR.$_GET["relativePath"]));
$dirInfo["directories"] = getDirectories(ROOT_DIR.$_GET["relativePath"]);
$dirInfo["files"] = getFiles(ROOT_DIR.$_GET["relativePath"]);

echo json_encode($dirInfo);

function getFiles($currentDirectory)
{
	$filesAndDirectories = scandir($currentDirectory);
	$files = array();

	foreach($filesAndDirectories as $fileAndDirectory)
	{
		if ($fileAndDirectory != "." &&
			$fileAndDirectory != ".." &&
			is_file($currentDirectory."/".$fileAndDirectory))
		{
			array_push($files, $fileAndDirectory);
		}
	}

	return $files;
}

function getDirectories($currentDirectory)
{
	$filesAndDirectories = scandir($currentDirectory);
	$directories = array();

	foreach($filesAndDirectories as $fileAndDirectory)
	{
		if ($fileAndDirectory != "." &&
			$fileAndDirectory != ".." &&
			is_dir($currentDirectory."/".$fileAndDirectory))
		{
			array_push($directories, $fileAndDirectory);
		}
	}

	return $directories;
}