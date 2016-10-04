<?
	include_once('../../inc/config.php');
	include_once("../../inc/app_start.php");
	
	$buffer = 60;
	$aDate = $_GET['d'];
	$aWeekday = strtolower(date("l",strtotime($aDate)));
	$dArr = explode("-",$aDate);
	//echo $aWeekday;
	$params = array('Yes');
	$aTimes = $app->db->rawQuery("SELECT * FROM lmg_scheduler_hours WHERE scheduler_".$aWeekday." = ?",$params);
	
	if(count($aTimes) > 0){
		$tStart = date("H:i",strtotime($aDate.' '.$aTimes[0]['scheduler_'.$aWeekday.'_start_time']));
		$tEnd = date("H:i",strtotime($aDate.' '.$aTimes[0]['scheduler_'.$aWeekday.'_end_time']));
		$t=$tStart;
		while($t<$tEnd){
			$tArr = explode(":",$t);
		?>
			<input type="radio" required="required" name="AppointmentTime" value="<?=date("g:i a",mktime($tArr[0],$tArr[1],0,$dArr[1],$dArr[2],$dArr[0]));?>" /> <?=date("g:i a",mktime($tArr[0],$tArr[1],0,$dArr[1],$dArr[2],$dArr[0]));?><br />
		<?
			$t = date("H:i", strtotime($t.' + '.$buffer.' minutes'));
		}
	}else{
		echo '<b><i>There are no appointment times available for the selected date.</i></b>';
	}
	
	include_once("../../inc/app_end.php");
?>
