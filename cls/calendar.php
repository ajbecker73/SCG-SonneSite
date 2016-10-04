<?
class Calendar extends App{
	
	public function load(){
		
		// OPEN DATABASE
			$this->db = new MysqliDB(DB_HOST ,DB_USER, DB_PASS, DB_NAME);
			$this->debugStr .= "database called<br>";
	
	}
	
	public function addBox($newBox){
		parent::addBox($newBox);
	}
	
	public function loadBox(){
		$this->addBox('<div class="callout-box">
			<h3 class="btn-primary">Upcoming Events</h3>
			<div class="callout-box-contents">
			'.$this->getUpcomingEvents(5).'</div></div>');
	}
	
	//FORMAT DATES FOR EVENTS
	public function writeDate($start,$end){
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
	
	public function getUpcomingEvents($eventLimit){
		$this->db = new MysqliDB(DB_HOST ,DB_USER, DB_PASS, DB_NAME);
		$params = array(date("Y-m-d"),$eventLimit);
		$upEvents = parent::$this->db->rawQuery('SELECT calendarStartDate,calendarEndDate,calendarTitle,calendarText 
					FROM lmg_calendar 
					WHERE calendarStartDate > ? 
					ORDER BY calendarStartDate ASC 
					LIMIT ?', $params);
			$evCount = count($upEvents);
			if($evCount<1){
				$evString = 'There are no upcoming events at this time';
				return $evString;
			}else{
				for($ev=0;$ev<$evCount;$ev++){
					$evString .= $this->writeDate($upEvents[$ev]['calendarStartDate'],$upEvents[$ev]['calendarEndDate'])."<br>";
					$evString .= $upEvents[$ev]['calendarTitle']."<br>";
					$evString .= '<a class="btn btn-mini" href="">View Event</a><hr />';
				}
				$evString .= '<br><br><a href="'.DOMAIN_ROOT.'plugins/lmg_calendar/calendar.php">View Full Calendar</a>';
				return $evString;
			}
	}

}
?>