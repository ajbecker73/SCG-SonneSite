<?
	function picList($location)
	{
		$retVal = '<table class="ceebox"><tr><td>';
		
		if($handle = opendir($location))
		{
			while(false != ($file = readdir($handle)))
			{
				if(is_file($location . "/" . $file))
				{
					$retVal .= '<a href="'.DOMAIN_ROOT.$location.'/'.$file.'"><img class="galImg" src="'.$location.'/'.$file.'" /></a>';
				}
			}												
		}else{
			$retVal = 'ERROR';
		}
		
		$retVal .= '</td></tr></table>';
		
		echo $retVal;
	}

	function right($str, $length) {
		 return substr($str, -$length);
	}
	
	function encrypt($str,$ky) {
		$eKey = $ky;
		$encString = md5($str);
		$rStr = right($encString,16);
		$encString = $encString.$rStr.md5($eKey);
		$encString = sha1($encString);
		return $encString;
	}
	
	function pass_gen($length){
	  $random= "";
	  srand((double)microtime()*1000000);
	  
	  $char_list = "ABCDEFGHJKMNPQRSTUVWXYZ";
	  $char_list .= "abcdefghjkmnpqrstuvwxyz";
	  $char_list .= "23456789";
	  $char_list .= "!@#$%&*";
	
	  for($i = 0; $i < $length; $i++){    
		$random .= substr($char_list,(rand()%(strlen($char_list))), 1);  
	  }
	  return $random;
	}
	
	function formatPhone($num){
		$num = preg_replace('/[^0-9]/', '', $num);
		 
		$len = strlen($num);
		if($len == 7)
		$num = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $num);
		elseif($len == 10)
		$num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $num);
		 
		return $num;
	}
	
	function writeDate($start,$end){ //date format (yyyy-mm-dd)
		$s = explode("-",$start);
		$e = explode("-",$end);
		
		$wd = date("M",mktime(0,0,0,ltrim($s[1],"0"),ltrim($s[2],"0"),$s[0]));
		$wd .= " ".date("j",mktime(0,0,0,ltrim($s[1],"0"),ltrim($s[2],"0"),$s[0]));
		
		if($s[0] < $e[0]){
			$wd .= ", ".date("Y",mktime(0,0,0,ltrim($s[1],"0"),ltrim($s[2],"0"),$s[0]));
			$wd .= " - ".date("M",mktime(0,0,0,ltrim($e[1],"0"),ltrim($e[2],"0"),$e[0]));
			$wd .= " ".date("j",mktime(0,0,0,ltrim($e[1],"0"),ltrim($e[2],"0"),$e[0]));
		}else{
			if($s[1] < $e[1]){
				$wd .= " - ".date("M",mktime(0,0,0,ltrim($e[1],"0"),ltrim($e[2],"0"),$e[0]));
				$wd .= " ".date("j",mktime(0,0,0,ltrim($e[1],"0"),ltrim($e[2],"0"),$e[0]));
			}else{
				if($s[2] < $e[2]){
					$wd .= " - ".date("j",mktime(0,0,0,ltrim($e[1],"0"),ltrim($e[2],"0"),$e[0]));
				}
			}
		}
		$wd .= ", ".date("Y",mktime(0,0,0,ltrim($e[1],"0"),ltrim($e[2],"0"),$e[0]));
		
		return $wd;
	}
	
	function writeTime($start,$end){ //date format (yyyy-mm-dd)
		$s = date("G:i",strtotime($start));
		$e = date("G:i",strtotime($end));
		
		if($s == $e){
			$wt = date("g:i a",strtotime($start));
		}else{
			if(date("a",strtotime($start)) == date("a",strtotime($end))){
				$wt = date("g:i",strtotime($start)).' - '. date("g:i a",strtotime($end));
			}else{
				$wt = date("g:i a",strtotime($start)).' - '. date("g:i a",strtotime($end));
			}
		}
		return $wt;
	}

	function LMGmail($to, $subject, $text ,$html, $from){
	if(is_string($to)){
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: ' .$from. "\r\n";     
		mail($to,$subject,$html,$headers);
	}elseif(is_array($to)){
		foreach($to as $toAddress){
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: ' .$from. "\r\n";     
			mail($toAddress,$subject,$html,$headers);
		}
	}
	return($error==false)?true:false;
	}

	function gen_pass($maxNum)
	{
		$passArray[0] = 0;
		$passArray[1] = 1;
		$passArray[2] = 2;
		$passArray[3] = 3;
		$passArray[4] = 4;
		$passArray[5] = 5;
		$passArray[6] = 6;
		$passArray[7] = 7;
		$passArray[8] = 8;
		$passArray[9] = 9;
		$passArray[10] = "A";
		$passArray[11] = "B";
		$passArray[12] = "C";
		$passArray[13] = "D";
		$passArray[14] = "E";
		$passArray[15] = "F";
		$passArray[16] = "G";
		$passArray[17] = "H";
		$passArray[18] = "I";
		$passArray[19] = "J";
		$passArray[20] = "K";
		$passArray[21] = "L";
		$passArray[22] = "M";
		$passArray[23] = "N";
		$passArray[24] = "O";
		$passArray[25] = "P";
		$passArray[26] = "Q";
		$passArray[27] = "R";
		$passArray[28] = "S";
		$passArray[29] = "T";
		$passArray[30] = "U";
		$passArray[31] = "V";
		$passArray[32] = "W";
		$passArray[33] = "X";
		$passArray[34] = "Y";
		$passArray[35] = "Z";
		
		$retVal = "";
		
		for($i = 0; $i < $maxNum; $i += 1)
		{
			$retVal .= $passArray[rand(0, 35)];
		}
		
		return $retVal;
	}
?>