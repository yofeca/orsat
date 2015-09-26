<?php
error_reporting(E_ALL ^ E_NOTICE);

function showThumb($src, $thumbWidth, $thumbHeight, $dest="") 
{
	if(!$_GET['abs']){
		
	}

	
	$info = pathinfo($src);

	// load image and get image size
	//$src = str_replace("%20", "", $src);
	//$src  = "http://google.com";
	//echo file_get_contents($src);
	//exit();
	$src = trim($src);
	
	/*
	if(strpos($src, "http://".$_SERVER['HTTP_HOST']."/media/") === 0 ){
		$src = str_replace("http://".$_SERVER['HTTP_HOST']."/media/", dirname(__FILE__)."/", $src);
		//echo "1";
	}
	else if(strpos($src, "http://e27.co/db//media") === 0 && 0){
		$src = str_replace("http://e27.co/db//media/", dirname(__FILE__)."/", $src);
	}
	else if(strpos($src, "http://www.startuplist.sg//media/") === 0 ){
		$src = str_replace("http://www.startuplist.sg//media/", dirname(__FILE__)."/", $src);
		//echo "3";
	}
	else if(strpos($src, "http://www.startuplist.sg/media/") === 0 ){
		$src = str_replace("http://www.startuplist.sg/media/", dirname(__FILE__)."/", $src);
		//echo "4";
	}
	else if(strpos($src, "http://27x.co//media/") === 0 ){
		$src = str_replace("http://27x.co//media/", dirname(__FILE__)."/", $src);
		//echo "5";
	}
	else if(strpos($src, "http://27x.co/media/") === 0 ){
		$src = str_replace("http://27x.co/media/", dirname(__FILE__)."/", $src);
		//echo "6";
	}
	else{
		$srcpieces = explode("/media/", $src);
		$src = dirname(__FILE__)."/".$srcpieces[1];
	
	*/
	//$srcpieces = explode("/media/", $src);
	//$src = dirname(__FILE__)."/".urldecode($srcpieces[1]);	
	
	
	if($_GET['showsrc']){
		echo $src;
		exit();	
	}
	
	if(!file_exists($src)){
		$src = dirname(__FILE__)."/empty.png";
	}

	$img = @imagecreatefromjpeg( $src );
	if(!$img){
		$img = @imagecreatefrompng ( $src );
	}
	if(!$img){
		$img = @imagecreatefromgif ( $src );
	}
	if(!$img){
		$img = @imagecreatefromwbmp ( $src );
	}
	if(!$img){
		$img = @imagecreatefromgd2 ( $src );
	}
	if(!$img){
		$img = @imagecreatefromgd2part ( $src );
	}
	if(!$img){
		$img = @imagecreatefromgd ( $src );
	}
	if(!$img){
		$img = @imagecreatefromstring ( $src );
	}
	if(!$img){
		$img = @imagecreatefromxbm ( $src );
	}
	if(!$img){
		$img = @imagecreatefromxpm ( $src );
	}
	
	if(!$img){
		
		return false;
	}	

	$width = imagesx( $img );
	$height = imagesy( $img );
	$new_width = $width;
	$new_height = $height;

	// calculate thumbnail size
	if($width>$height)
	{
		if($thumbWidth<$width)
		{
			$new_width = $thumbWidth;
			$new_height = floor( $height * ( $thumbWidth / $width ) );
		}
	}
	else
	{
		if($thumbHeight<$height)
		{
			$new_height = $thumbHeight;
			$new_width = floor( $width * ( $thumbHeight / $height ) );
		}
	}

	if($_GET['square']) 
	{
		if($new_width>$new_height){
			$side = $new_width;
		}
		else{
			$side = $new_height;
		}
		$tmp_img = imagecreatetruecolor( $side, $side );
		$white = imagecolorallocate($tmp_img, 255, 255, 255);
		imagefill($tmp_img, 0, 0, $white);
		
		imagecopyresampled( $tmp_img, $img, (($side-$new_width)/2), (($side-$new_height)/2), 0, 0, $new_width, $new_height, $width, $height );
	} 
	else if($_GET['thumb']) 
	{
		if($new_width>$new_height){
			$side = $new_width;
		}
		else{
			$side = $new_height;
		}
		$sidex = $side*1.269;
		$tmp_img = imagecreatetruecolor( $sidex, $side );
		$white = imagecolorallocate($tmp_img, 255, 255, 255);
		imagefill($tmp_img, 0, 0, $white);
		
		imagecopyresampled( $tmp_img, $img, (($sidex-$new_width)/2), (($side-$new_height)/2), 0, 0, $new_width, $new_height, $width, $height );
	}
	else
	{
		// create a new temporary image
		$stamp = "stamp.png";
		$stamp = @imagecreatefrompng ( $stamp );

		$tmp_img = imagecreatetruecolor( $new_width, $new_height );
		$white = imagecolorallocate($tmp_img, 255, 255, 255);
		//image,x,y,color
		imagefill($tmp_img, 0, 0, $white);
		// copy and resize old image into new image 
		//dstimg,srcimage,dstx,dsty,srcx,srcy,dstw,dsth,srcw,srch
		
		$opt = $_GET['o'] + 1;
		if($opt==1){
			imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		}else{
			imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			imagecopyresampled( $tmp_img, $stamp, 0, 150, 0, 0, $new_width, imagesy( $stamp ), imagesx( $stamp ), imagesy( $stamp ) );
		}
		
		//@Set Stamp using image copy
		//bool imagecopy ( $dst_im , $src_im , $dst_x , $dst_y , $src_x , $src_y , $src_w , $src_h )
		/*$sx = imagesx($stamp);
		$sy = imagesy($stamp);
		imagecopy($tmp_img, $stamp, 0, 100, 0, 0, imagesx($stamp), $sy );
		*/
	}
	

	if(!trim($dest)){
		imagepng( $tmp_img , null, 0);
	}
	else{
		//save and image cache
		//@imagepng ( $tmp_img , $dest, 0);
		imagepng ( $tmp_img , null, 0);
	}
	// save thumbnail into a file
	
} //@End of showThumb

$mx = $_GET['mx']*1;
$mxh = $_GET['mxh']*1;
$mxw = $_GET['mxw']*1;

if($mx==0){
	$mx = 10000;
}

@mkdir(dirname(__FILE__)."/imgcache", 0777);
$p = urldecode($_GET['p']);
$p = dirname($p)."/".rawurlencode(basename($p));

//if base64 encoded data
if( base64_encode(base64_decode($_GET['p'])) === $_GET['p']){
    $p = base64_decode($_GET['p']);
}
//echo $p;
if($_GET['square']){
	$md5file = dirname(__FILE__)."/imgcache/".md5($p)."_mx".$mx."_mxh".$mxh."_mxw".$mxw."_square.png";
}
else{
	$md5file = dirname(__FILE__)."/imgcache/".md5($p)."_mx".$mx."_mxh".$mxh."_mxw".$mxw.".png";
}


if(file_exists($md5file)&&!$_GET['nocache']){
	if($mx){
		if(!$_GET['nohead']){
			header('Content-Type: image/png');
		}
		//echo $md5file;
		echo file_get_contents($md5file);
	}
	else if($mxw&&$mxh){
		if(!$_GET['nohead']){
			header('Content-Type: image/png');
		}
		echo file_get_contents($md5file);
	}
	else
	{
		if(!$_GET['nohead']){
			header('Content-Type: image/png');
		}
		echo file_get_contents($md5file);
	}
}
else{

	if($mx){

		if(!$_GET['nohead']){
			header('Content-Type: image/png');
		}

		showThumb($p, $mx, $mx, $md5file);	
	}
	else if($mxw&&$mxh){
		if(!$_GET['nohead']){
			header('Content-Type: image/png');
		}
		showThumb($p, $mxw, $mxh, $md5file);	
	}
	else{

		if(!$_GET['nohead']){
			header('Content-Type: image/png');
		}
		showThumb($p, 1000, 1000, $md5file);
	}
}
?>