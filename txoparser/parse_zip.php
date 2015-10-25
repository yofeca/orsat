<?php
//home/larry/public_html/txoparser/database.php
include_once("D:\\xampp\\htdocs\\orsat\\txoparser\\database.php");

function fetch_zip_files(){

	$sql = "SELECT * FROM `files` WHERE `flag`='0' AND `type`='zip' LIMIT 1000";
	$r = dbQuery($sql);

	return $r;
		
}//End Of get_files();



function unzip_files($path=""){

	echo 'Extracting ' . basename($path);

	if(! file_exists( $path ) ) return;
	
	$dir = dirname($path);

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

//$path = "D:\\xampp\\htdocs\\orsat\\txoparser\\dumps";
//parseTXO($path."\\"."2ABBJ18D.TX0");
///home/larry/public_html/txoparser/dumps
$path = "D:\\xampp\\htdocs\\orsat\\txoparser\\dumps";

// $p = get_files($path);
$files = fetch_zip_files();

if($files){ //check if there are files not processed
	
	$count = count($files);
	for($i=0; $i<$count; $i++){
		
		if(unzip_files($path."\\".$files[$i]['filename'])){
			//delete_db_file( $files[$i]['id'] );
		}else{
			echo " - Failed!.\n";
		}
	}

	for($i=0; $i<$count; $i++){
		$cmd = "php -f D:\\xampp\\htdocs\\orsat\\txoparser\\dir_import.php " . str_replace( " ","__",str_replace(".zip", "", $files[$i]['filename'] ) );
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