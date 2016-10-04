<?
$CurrentMonth = date("m");;
$CurrentYear = date("Y");;
?>
<div style="margin-bottom:20px; margin-top:10px;">
	<a class="btn btn-mini btn-primary" href="calendar/calendar"><?=date("M Y",mktime(0,0,0,$CurrentMonth,1,$CurrentYear));?></a>
	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+1,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+1,1,$CurrentYear));?></a>
	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+2,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+2,1,$CurrentYear));?></a>
	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+3,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+3,1,$CurrentYear));?></a>
	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+4,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+4,1,$CurrentYear));?></a>
	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+5,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+5,1,$CurrentYear));?></a>

	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+6,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+6,1,$CurrentYear));?></a>
	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+7,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+7,1,$CurrentYear));?></a>
	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+8,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+8,1,$CurrentYear));?></a>
	<a class="btn btn-mini btn-primary" href="calendar/calendar?date=<?=date("m-d-Y",mktime(0,0,0,$CurrentMonth+9,1,$CurrentYear));?>"><?=date("M Y",mktime(0,0,0,$CurrentMonth+9,1,$CurrentYear));?></a>
</div>
<?
if($_GET['date'] != ""){
	$ds = explode("-",$_GET['date']);
	$MonthNum = date("m",mktime(0,0,0,$ds[0],1,0));
	$MonthName = date("F",mktime(0,0,0,$MonthNum,1,0));
	$Year = date("Y",mktime(0,0,0,$MonthNum,1,$ds[2]));
}else{
	$MonthNum = $CurrentMonth;
	$MonthName = date("F",mktime(0,0,0,$MonthNum,1,0));
	$Year = $CurrentYear;
}
echo "<h2>".$MonthName." ".$Year."</h2>";
?>
<table class="calendar">
	<tr>
		<td class="cal_heading">Sunday</td>
		<td class="cal_heading">Monday</td>
		<td class="cal_heading">Tuesday</td>
		<td class="cal_heading">Wednesday</td>
		<td class="cal_heading">Thursday</td>
		<td class="cal_heading">Friday</td>
		<td class="cal_heading">Saturday</td>
	</tr>
	<?
	$dom = 1;
	$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $MonthNum, $Year);
	$jd=cal_to_jd(CAL_GREGORIAN,$MonthNum,1,$Year);
	$firstDay = (jddayofweek($jd,0));
	$d = -1;
	$week = 1;
	?>
	<tr>
		<?
		while($dom < 2){
			$dayName = (jddayofweek($d,0));
			if($firstDay == $dayName){
				?>
				<td class="cal_cell<? echo (date("YMd") == date("YMd",mktime(0,0,0,$MonthNum,$dom,$Year)) ? ' active' : '');?>">
				<div class="cal_cell_content"><b><?=$dom;?></b><br>
					<?
						$params = array($Year."-".str_pad($MonthNum,2,0,STR_PAD_LEFT)."-".str_pad($dom,2,0,STR_PAD_LEFT));
						$result = $app->db->rawQuery("SELECT * From lmg_calendar WHERE cal_startdate = ? ORDER BY id ASC",$params);
						$rNum = count($result);
						for($r=0;$r<$rNum;$r++){
							?>
							<b class="cal_title"><?=stripslashes($result[$r]['cal_title']);?></b>
							<br><a class="label label-info" href="<?=DOMAIN_ROOT;?>calendar/event-details?id=<?=$result[$r]['id'];?>">Details</a>
							<?
							$valid = "yes";
							if($result[$r]['cal_pdf'] != ""){
								?>
								<br><a class="label label-warning" href="<?=DOMAIN_ROOT;?>pdf/<?=$result[$r]['cal_pdf'];?>" target="_blank">Flyer</a>
								<?
							}
		
							if($result[$r]['cal_registration'] == "yes"){
								?>
								<br><a class="label label-success" href="<?=DOMAIN_ROOT;?>calendar/registration?id=<?=$result[$r]['id'];?>">Register</a>
								<?
							}
							echo "<hr />";
						}
						if (!$valid){
							echo("");
						}
						$started = "yes";
					?>
				</div>
				</td>
				<?
			}else{
				?>
				<td></td>
				<?
			}
			if($started == "yes"){
				$dom ++;
			}
			if($d == 5){
				?>
				</tr>
				<tr>
				<?
				$d = -1;
				$week ++;
			}
			$d ++;
		}
		while($dom < $daysInMonth+1){
			?>
			<td class="cal_cell<? echo (date("YMd") == date("YMd",mktime(0,0,0,$MonthNum,$dom,$Year)) ? ' active' : '');?>">
			<div class="cal_cell_content"><b><?=$dom;?></b><br>
			<?
				$params = array($Year."-".str_pad($MonthNum,2,0,STR_PAD_LEFT)."-".str_pad($dom,2,0,STR_PAD_LEFT));
				$result = $app->db->rawQuery("SELECT * From lmg_calendar WHERE cal_startdate = ? ORDER BY id ASC",$params);
				$rNum = count($result);
				for($r=0;$r<$rNum;$r++){
					?>
					<b class="cal_title"><?=stripslashes($result[$r]['cal_title']);?></b>
					<br><a class="label label-info" href="<?=DOMAIN_ROOT;?>calendar/event-details?id=<?=$result[$r]['id'];?>">Details</a>
					<?
					$valid = "yes";
					if($result[$r]['cal_pdf'] != ""){
						?>
						<br><a class="label label-warning" href="<?=DOMAIN_ROOT;?>pdf/<?=$result[$r]['cal_pdf'];?>" target="_blank">Flyer</a>
						<?
					}

					if($result[$r]['cal_registration'] == "yes"){
						?>
						<br><a class="label label-success" href="<?=DOMAIN_ROOT;?>calendar/registration?id=<?=$result[$r]['id'];?>">Register</a>
						<?
					}
					echo "<hr />";
				}
				if (!$valid){
					echo("");
				}
				$started = "yes";
			?>
			</div>
			</td>
			<?
			if($week > 1){
				if($d == 6){
					?>
					</tr>
					<tr>
					<?
					$d = -1;
					$week ++;
				}
			}else{
				if($d == 5){
					?>
					</tr>
					<tr>
					<?
					$d = -1;
					$week ++;
				}
			}
				$dom ++;
			$d ++;
		}
		?>
	</tr>
</table>