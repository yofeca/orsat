<?php
include_once(dirname(__FILE__)."/database.php");

function pre($a){
	echo "<pre>";
	print_r($a);
	echo "</pre>";
}
function parseTXO($filepath){ //filepath should be absolute path of the file
	$csv = array_map('str_getcsv', file($filepath));
	$tcsv = count($csv);
	
	$header_counter = 0;
	
	$filename = basename($filepath);
	
	$sql = "select * from `txo_dumps` where `filename`='".mysql_real_escape_string($filename)."'";
	
	$r = dbQuery($sql);
	
	//if(!isset($r[0])){
		if(true){
		//headers
		for($i=1; $i<$tcsv; $i++){
			$header_counter++;
			
			$t = count($csv[$i]);
			$key = "";
			$value = "";

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
								//echo str_replace(":","xxx",$index) . "<br>";
								$value .= trim($csv[$i][$k])." ";
							}
							else{
								//if(!str_replace(":","")=="")
								$k=$t;
							}
						}
						$sqlext[] = "`".trim($key)."` = '".mysql_real_escape_string(trim($value))."'";
					}
				}else{
					break 2;
				}
			}
		}//end of header loop
		
		//get the year of the file
		$file_date = explode("=",$sqlext[1]);
		$dt = date_parse($file_date[1]);
		$file_year = $dt['year'];
		
		$sql = "insert into `txo_dumps` set
			`fn_id` = '".mysql_real_escape_string($file_year.trim($filename))."',
			`filename`='".mysql_real_escape_string($filename)."', 
			`dateadded`=NOW(),
			".implode(", ", $sqlext);
		
		$sql = str_replace(", `` = ''","",$sql);
		
		$r = dbQuery($sql);
		$insert_id = $r['mysql_insert_id'];
		$txo_id = $insert_id;

		//plots
		for($i=$header_counter+6; $i<$tcsv; $i++){
			
			$subtcsv_count = count($csv[$i]);

			if($subtcsv_count > 4 && (trim($csv[$i][0]) || trim($csv[$i][1]))){
				$sql = "insert into `column_values` set
				`fn_id` = '".mysql_real_escape_string($file_year.trim($filename))."',
				`filename` = '".mysql_real_escape_string(trim($filename))."',
				`Peak` = '".mysql_real_escape_string(trim($csv[$i][0]))."',
				`Component` = '".mysql_real_escape_string(trim($csv[$i][1]))."',
				`Amount` = '".mysql_real_escape_string(trim($csv[$i][2]))."',
				`Time` = '".mysql_real_escape_string(trim($csv[$i][3]))."',
				`Area` = '".mysql_real_escape_string(trim($csv[$i][4]))."',";
				
				if(isset($csv[$i][5])){
					$sql .= "`Method RT` = '".mysql_real_escape_string(trim($csv[$i][5]))."',";
				}
				
				$sql .= "`txo_id` = '".$txo_id."',`dateadded` = NOW()";
				$r = dbQuery($sql);
				$insert_id = $r['mysql_insert_id'];
			}else{ 
				//store the total values
				if( $subtcsv_count > 4 ){
					if($csv[$i][2] == "------" && $csv[$i][4] == "------"){
						
						$sql = "insert into `txo_tvalues` set
							`fn_id` = '".mysql_real_escape_string($file_year.trim($filename))."',
							`filename` = '".mysql_real_escape_string(trim($filename))."',
							`pp_carbon` = '".mysql_real_escape_string(trim($csv[$i+1][2]))."',
							`area` = '".mysql_real_escape_string(trim($csv[$i+1][4]))."',";
							
						if(isset($csv[$i][5])){
							$sql .= "`method_rt` = '".mysql_real_escape_string(trim($csv[$i+1][5]))."',";
						}
						
						$sql .= "`txo_id` = '".$txo_id."', `dateadded` = NOW()";
						$r = dbQuery($sql);
						break;
					}
				}
			}
		} //end of plot loop
		return true;
	} //end of dQuery();
	return false;
}

//Data/35FLORESVILLEJUNE/35BBF03X.TX0
//Data/2ACampus10-18-14/2APSJ18X.TX0
//Data/some/KPSB28U.TX0
$path = dirname(__FILE__)."/Data/2ACampus10-18-14/2APSJ18X.TX0";


if(parseTXO($path)){
	echo "TXO File Successfully Imported!\n";
}
else{
	echo "TXO File Import Failed!";
}

?>