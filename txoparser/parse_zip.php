<?php

if(true){
	$base_dir = "D:\\xampp\\htdocs\\orsat\\txoparser\\";
}else{
	$base_dir = "/home/nmgdev/public_html/orsat/txoparser/";
}

include_once( $base_dir . "database.php" );

function fetch_db_zip_files()
{

	$sql = "SELECT * FROM `files` WHERE `flag`='0' AND `type`='zip' AND `active`= '0' LIMIT 50";
	$r = dbQuery($sql);

	$count = count($r);

	for( $i=0; $i<$count; $i++ )
	{
		
		$id = $r[$i]['id'];
		$sql = "UPDATE `files` SET `active`= '1' WHERE id = $id";
		$q = dbQuery($sql);
	}

	return $r;
		
}//End Of get_files();

function unzip_files($path=""){

	echo 'Extracting ' . basename($path);
	
	if(! file_exists( $path ) ) return;
	
	$dir = str_replace(".zip", "\\", $path );

	$zip = new ZipArchive;
	$res = $zip->open($path);
	if($res === TRUE){
		$zip->extractTo($dir);
		$zip->close();
		echo ' done...';
		return unlink($path);
	}else{
		echo ' error...';
	}

}

function delete_db_file($file_id){

	$sql = "DELETE FROM `files` WHERE id='" . $file_id. "'";
	$r = dbQuery($sql);

}

$path = $base_dir . "dumps";

// $p = get_files($path);
$files = fetch_db_zip_files();
$count = count($files);

if($files)
{ 
	//check if there are files not processed
	for($i=0; $i<$count; $i++)
	{
		
		if(unzip_files($path."\\".$files[$i]['filename']))
		{
			delete_db_file( $files[$i]['id'] );
		}
		else
		{
			echo " - Failed!.\n";
		}
	}
}

if($files)
{ 
	for($i=0; $i<$count; $i++)
	{
		$cmd = "php -f " . $base_dir . "dir_import.php " . str_replace( " ","__",str_replace(".zip", "", $files[$i]['filename'] ) );
		//echo $cmd;
		execInBackground($cmd);
	}
}

function execInBackground($cmd) { 
    if (substr(php_uname(), 0, 7) == "Windows"){ 
       //pclose(popen("start /B " . $cmd, "r"));
       pclose( popen("start " . $cmd, "r") );  
    } 
    else { 
        exec($cmd . " > /dev/null &");   
    } 
}

?>