<h1>Registrations Reports</h1>
<?
if($_POST['Submit'] == 'Run Report'){
	$params = array($_POST['eid'],'rid');
	$sql = 'SELECT * FROM lmg_registrations WHERE eid = ? ';
	$sql .= 'ORDER BY ? ASC';

	$query = $app->db->rawQuery($sql,$params);
	$records = count($query);
}
$_SESSION['mSearchParams'] = $params;
$_SESSION['mSearchQL'] = $sql;
?>
<div class="well" style="padding:10px; margin:10px;">
	<h4>Enter report criteria</h4>
	<form action="<?=DOMAIN_ROOT;?>backoffice/?url=searchRegistrations" method="post" name="MReport">
	<p class="pull-left" style="margin:0 40px 0 0;">
		<label><b>Event</b></label>
		<select name="eid" style="width:auto;">
			<option value=""></option>
			<?
			$events = $app->db->orderBy('cal_startdate','DESC')->get('lmg_calendar');
			for($e=0;$e<count($events);$e++){
				?>
				<option value="<?=$events[$e]['id'];?>"><b><?=date("F d, Y",strtotime($events[$e]['cal_startdate']));?></b> - <?=$events[$e]['cal_title'];?></option>
				<?
			}
			?>
		</select>
	
	</p>
	<p><input class="btn" type="submit" name="Submit" value="Run Report" /></p>
	</form>
</div>
<div style="padding:10px; margin:10px;">
	<div class="pull-right" style="font-size:18px; line-height:25px;"><a href="<?=DOMAIN_ROOT;?>backoffice/exportReport2.php" target="_blank" style="color:#3f7a08; text-decoration:none;"><img src="<?=DOMAIN_ROOT;?>img/excel-icon.png" /> Export to Excel</a></div>
	<?
	echo '<b>'.$records.' registrations(s) found matching your query.</b><br />';
	?>
	<table class="table table-condensed table-striped">
		<tr class="info">
			<td><b>Reg#</b></td>
			<td><b>Event</b></td>
			<td><b>Name</b></td>
			<td><b>Company</b></td>
			<td><b>Phone</b></td>
			<td><b>Email</b></td>
			<td><b>Attendees</b></td>
		</tr>
		<?
		for($r=0;$r<$records;$r++){
			$jDate = explode('-',$query[$r]['date_joined']);
			$eDate = explode('-',$query[$r]['date_expires']);
			?>
			<tr>
				<td><?=$query[$r]['eid'];?></td>
				<td><?
					$event = $app->db->where('id',$query[$r]['eid'])->get('lmg_calendar');
					echo $event[0]['cal_title'];
					?>
				</td>
				<td><?=$query[$r]['reg_firstname'].' '.$query[$r]['reg_lastname'];?></td>
				<td><?=$query[$r]['reg_company'];?></td>
				<td><?=$query[$r]['reg_phone'];?></td>
				<td><?=$query[$r]['reg_email'];?></td>
				<td>
				<?
					$v = unserialize($query[$r]['reg_attendees']);
					$ct = count($v[0]);
					for($c=0;$c<$ct;$c++){
						echo $v[0][$c].' '.$v[1][$c].'<br />';
					}
				?>
				</td>
			</tr>
			<?
		}
		?>
	</table>
</div>