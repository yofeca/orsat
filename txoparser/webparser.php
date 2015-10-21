<?php
//home/larry/public_html
include_once("D:\\xampp\\htdocs\\orsat\\txoparser\\database.php");

function pre($a){
	echo "<pre>";
	print_r($a);
	echo "</pre>";
}

function fetch_txo($filepath){
	$filename = basename($filepath);
	$sql = "SELECT id, filename FROM `txo_dumps` WHERE `filename`='" . mysql_real_escape_string($filename) . "' LIMIT 1";
	$r = dbQuery($sql);
	return $r[0];
}

function fetch_site($instrument_name){	

	$instrument_name = str_replace(" ", "", $instrument_name);
	
	$sql = "SELECT id FROM `sites` WHERE `instrument_name` LIKE '%" . strtoupper($instrument_name) . "%' LIMIT 1";
	$r = dbQuery($sql);
	
	if($r){
		return $r[0]['id'];
	}else{
		$sql = "INSERT INTO `sites` SET `instrument_name`= '".mysql_real_escape_string(strtoupper($instrument_name))."'";
		$r = dbQuery($sql);

		return $r['mysql_insert_id'];
	}
}

function fetch_sample_type($filename,$sample_name){
	
	$file_designator = substr($filename,-9,1);
	$sample_name = str_replace(" - ", "-", $sample_name);
	$search_val = substr($sample_name,0,5);

	$sql = "SELECT id FROM `sample_types` 
			WHERE `name` LIKE '%" . strtoupper($search_val) . "%' OR file_designator='" . $file_designator . "' LIMIT 1";
	$r = dbQuery($sql);
	
	if($r){
		return $r[0]['id'];
	}else{
		$sql = "INSERT INTO `sample_types` SET `name`= '" . mysql_real_escape_string(strtoupper($sample_name)) . "'";
		$r = dbQuery($sql);

		return $r['mysql_insert_id'];
	}
}

function convert_to_date($str){
	$d = date('Y-m-d H:i:s',strtotime($str));
	return $d;
}

function parseTXO($filepath){ //filepath should be absolute path of the file

	$site_name = $channel = $sample_name = $data_acquisition_time='';
	
	$filename = basename($filepath);

	$csv = array_map('str_getcsv', file($filepath));

	$totalcsv = count($csv);

	$r = fetch_txo($filepath); //fetch txo
	
	if(!isset($r[0])){	
	
		//start of header loop array
		$header_counter = 1;
		for($i=1; $i<$totalcsv; $i++){
			
			$t = count($csv[$i]); $key = ""; $value = "";

			for($j=0; $j<$t; $j++){
				
				$index = trim($csv[$i][$j]);
				if(substr($index, -5, 5) != "====="){
					
					if(substr($index, -1, 1)==":"){
						$value = "";
						$key = trim(trim($csv[$i][$j]), ":");
						//get the values
						for($k=($j+1); $k<$t; $k++){
							$index = trim($csv[$i][$k]);
							if(!(substr($index, -1, 1)==":")){
								$value .= trim($csv[$i][$k])." ";
							}
							else{
								$k=$t;
							}
						}
						$key = trim($key);
						$key = str_replace(' #', '', $key);
						$key = str_replace(array(' ', '/'), '_', $key);

						if(strtolower($key)=='date') $value = convert_to_date($value);
						if(strtolower($key)=='data_acquisition_time'){
							$value = convert_to_date($value);
							$data_acquisition_time = $value;
						}
						$sqlext[] = "`". strtolower($key) ."` = '".mysql_real_escape_string(trim($value))."'";

						if(strtolower($key)=='instrument_name') $site_name = mysql_real_escape_string(trim($value));
						if(strtolower($key)=='channel') $channel = mysql_real_escape_string(trim($value));
						if(strtolower($key)=='sample_name') $sample_name = mysql_real_escape_string(trim($value));
					}
				}else{
					break 2;
				}
			}

			$header_counter++;
		}//end of header loop array


		$file_year = date('Y',strtotime($data_acquisition_time)); //get the year of the file
	
		$site_id = fetch_site($site_name); //check site exist, otherwise add
		$sample_type_id = fetch_sample_type($filename,$sample_name);

		$sql = "INSERT INTO `txo_dumps` SET
			`filename_id` = '" .mysql_real_escape_string($file_year.trim($filename)). "',
			`filename`='" .mysql_real_escape_string($filename)."',
			`site_id` = " .$site_id. ",
			`sample_type_id` = " .$sample_type_id. ",
			".implode(", ", $sqlext);

		$sql = str_replace(", `` = ''","",$sql); //removes unnecessary columns ex. `` = ''

		$r = dbQuery($sql);
		$txo_insert_id = $r['mysql_insert_id'];

		//column values (PLOT A / PLOT B)
		for($i=$header_counter+6; $i<$totalcsv; $i++){
			
			$subtotalcsv_count = count($csv[$i]);
			
			if($subtotalcsv_count > 4 && (trim($csv[$i][0]) || trim($csv[$i][1]))){
				
				$sql = "INSERT INTO `component_values` SET
					`peak` = '".mysql_real_escape_string(trim($csv[$i][0]))."',
					`component_name` = '".mysql_real_escape_string(trim($csv[$i][1]))."',
					`amount` = '".mysql_real_escape_string(trim($csv[$i][2]))."',
					`time` = '".mysql_real_escape_string(trim($csv[$i][3]))."',
					`area` = '".mysql_real_escape_string(trim($csv[$i][4]))."',
					`filename` = '".mysql_real_escape_string(trim($filename))."',
					`status` = '0',
					`site_id` = '".$site_id."',
					`site_name` = '".$site_name."',
					`channel` = '".$channel."',
					`data_acquisition_time` = '".$data_acquisition_time."',
					`sample_id` = '".$sample_type_id."',
					`sample_name` = '".$sample_name."',";
				
				if(isset($csv[$i][5])){
					$sql .= "`method_rt` = '".mysql_real_escape_string(trim($csv[$i][5]))."',";
				}
					$sql .= "`txo_dump_id` = '".$txo_insert_id."'";
				
				$r = dbQuery($sql);
			
			}else{ 

				//store the total values
				if( $subtotalcsv_count > 4 ){
					if($csv[$i][2] == "------" && $csv[$i][4] == "------"){
						$sql = "INSERT INTO `txo_total_components` SET
							`filename` = '".mysql_real_escape_string(trim($filename))."',
							`pp_carbon` = '".mysql_real_escape_string(trim($csv[$i+1][2]))."',
							`area` = '".mysql_real_escape_string(trim($csv[$i+1][4]))."',
							`site_id` = '".$site_id."',
							`site_name` = '".$site_name."',
							`channel` = '".$channel."',
							`data_acquisition_time` = '".$data_acquisition_time."',
							`sample_id` = '".$sample_type_id."',
							`sample_name` = '".$sample_name."',";
						
						if(isset($csv[$i][5])){
							$sql .= "`method_rt` = '".mysql_real_escape_string(trim($csv[$i+1][5]))."',";
						}
						
						$sql .= "`txo_dump_id` = '" . $txo_insert_id . "', `ascii_file` = '" . mysql_real_escape_string(trim($csv[$totalcsv-1][1])). "'";
						$r = dbQuery($sql);
						break;
					}
				}
			}
		}//end of plot loop

		//$sql = "UPDATE `files` SET flag='1' WHERE filename='" . $filename . "'";
		$sql = "DELETE FROM `files` WHERE filename='" . $filename . "'";
		$r = dbQuery($sql);

		unlink($filepath);
		return true;

	} //end of dQuery();

	return false;

}//End Of parseTXO();


function get_files($path="") {

	if($path){

		$dir_list = array_diff( scanDIR( $path ), array( '..' , '.' ) );
		
		if( $dir_list ) {
			natsort( $dir_list );
			$sorted_list = array();
			foreach( $dir_list as $dl ){
				array_push( $sorted_list, $dl );
			}

			return $sorted_list;
		} else {
			exit( "\nError! Missing Txo.\n" );
		}

	}else{

		$sql = "SELECT filename FROM `files` WHERE `flag`='0' LIMIT 1000";
		$r = dbQuery($sql);

		return $r;
		
	}
}//End Of get_files();

//$path = "D:\\xampp\\htdocs\\orsat\\txoparser\\dumps";
//parseTXO($path."\\"."2ABBJ18D.TX0");
///home/larry/public_html/txoparser
$path = "D:\\xampp\\htdocs\\orsat\\txoparser\\dumps";

// $p = get_files($path);
$p = get_files();

if($p){ //check if there are files not processed
	$count = count($p) - 1;
	for($i=$count; $i>=0; $i--){
		if(parseTXO($path."\\".$p[$i]['filename'])){
			echo "TXO File Successfully Imported!\n";
		}else{
			echo "TXO File Import Failed! The file is already in the database.\n";
		}
	}
}
?>