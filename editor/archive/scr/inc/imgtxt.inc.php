<?php

define("_AVERAGE_WIDTHFONT",0.8);

class putTxtOnImg
{
	function Message($msg)
	{	$msg = (!$msg) ? "" : $msg;
		$this->message = $msg;
	}

	function FontSize($fsize)
	{	$fsize = (!$fsize) ? 5 : $fsize;
		$this->fontSize	= $fsize;
	}
	
	function Font($font)
	{	if (!file_exists($font))
		{	die("Can't Find Specified Font: {$font}");
		}
		$this->font	= $font;
	}
	
	function Coordinate($x,$y)
	{	$x = (!$x) ? 0 : $x;
		$y = (!$y) ? 0 : $y;
		$this->x = $x;
		$this->y = $y;
	}
	
	function Angle($angle)
	{	$angle = (!$angle) ? 0 : $angle;
		$this->angle = $angle;
	}
	
	function Colors($colors)
	{	$this->colorR = hexdec(substr($colors,0,2));
		$this->colorG = hexdec(substr($colors,2,2));
		$this->colorB = hexdec(substr($colors,4,2));
	}
	
	function WriteTXT($height,$width,$bg,$imgQual)
	{	$incrementLine	= 0;
		$tempX	= $this->x;
		$tempY	= $this->y;
		
		$im = imagecreate($width,$height);
		$bg = imagecolorallocate($im,hexdec(substr($bg,0,2)),hexdec(substr($bg,2,2)),hexdec(substr($bg,4,2)));
				
		$text_color	= imagecolorallocate($im, $this->colorR, $this->colorG, $this->colorB);
		if ($this->colorShadow == 1)
		{	$shadow = imagecolorallocate($im, $this->colorSR, $this->colorSG, $this->colorSB);
		}
		
		imagettftext(	$im
						,$this->fontSize
						,$this->angle
						,$tempX
						,$this->y
						,$text_color
						,$this->font
						,$this->message
						);
		
		return $im;
//		header("Content-type: image/jpeg");
//		imagejpeg($im,"",$imgQual);
//		imagedestroy($im);
	}
}

?>