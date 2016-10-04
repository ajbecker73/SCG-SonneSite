<h1>Members Reports</h1>
<?
if($_POST['Submit'] == 'Run Report'){
	$active = $_POST['Active'];
	
	$params = array($active);
	if($_POST['mbr_type'] != ''){
		$params[count($params)] = $_POST['mbr_type'];
	}
	if($_POST['dues'] != ''){
		$params[count($params)] = $_POST['dues'];
	}
	if($_POST['sMonth'] != '' && $_POST['sDay'] != '' && $_POST['sYear'] != '' && $_POST['eMonth'] != '' && $_POST['eDay'] != '' && $_POST['eYear'] != ''){
		$params[count($params)] = date('Y-m-d',mktime(0,0,0,$_POST['sMonth'],$_POST['sDay'],$_POST['sYear']));
		$params[count($params)] = date('Y-m-d',mktime(0,0,0,$_POST['eMonth'],$_POST['eDay'],$_POST['eYear']));
	}
	$params[count($params)] = 'mbr_lastname';

		$sql = 'SELECT * FROM lmg_members WHERE mbr_active = ? ';
		if($_POST['mbr_type'] != ''){
			$sql .= 'AND mbr_type = ? ';
		}
		if($_POST['dues'] != ''){
			$sql .= 'AND mbr_dues = ? ';
		}
		if($_POST['sMonth'] != '' && $_POST['sDay'] != '' && $_POST['sYear'] != ''){
			$sql .= 'AND date_expires BETWEEN ? and ? ';
		}
		$sql .= 'ORDER BY ? ASC';
	
			$query = $app->db->rawQuery($sql,$params);
			$records = count($query);
}else{
	$params = array('mbr_lastname');
	$sql = 'SELECT * FROM lmg_members ORDER BY ? ASC';
	$query = $app->db->rawQuery($sql,$params);
	$records = count($query);
}
$_SESSION['mSearchParams'] = $params;
$_SESSION['mSearchQL'] = $sql;
?>
<div class="well" style="padding:10px; margin:10px;">
	<h4>Enter report criteria</h4>
	<form action="<?=DOMAIN_ROOT;?>backoffice/?url=searchMembers" method="post" name="MReport">
	<p class="pull-left" style="margin:0 40px 0 0;">
		<label><b>Active</b></label>
		<input type="radio" name="Active" value="1" checked /> Yes
		&nbsp;&nbsp;&nbsp;
		<input type="radio" name="Active" value="0"<? echo($active == '0' ? ' checked' : ''); ?> /> No
	</p>
	<p class="pull-left" style="margin:0 40px 0 0;">
		<label><b>Dues Level</b></label>
		<select name="dues" style="width:auto;">
			<option value=""></option>
			<option<? echo ($_POST['dues'] == '900' ? ' selected' : ''); ?> value="900">900</option>
			<option<? echo ($_POST['dues'] == '1100' ? ' selected' : ''); ?> value="1100">1100</option>
			<option<? echo ($_POST['dues'] == '1500' ? ' selected' : ''); ?> value="1500">1500</option>
			<option<? echo ($_POST['dues'] == '1950' ? ' selected' : ''); ?> value="1950">1950</option>
			<option<? echo ($_POST['dues'] == '2150' ? ' selected' : ''); ?> value="2150">2150</option>
		</select>
	
	</p>
	<p class="pull-left" style="margin:0 40px 0 0;">
		<label><b>Expires Between</b></label>
		<select name="sMonth" style="width:auto;">
			<option value=""></option>
			<?
			for($m=1;$m<13;$m++){
				?>
				<option<? echo ($_POST['sMonth'] == $m ? ' selected' : ''); ?> value="<?=date('m',mktime(0,0,0,$m,1,date('Y')));?>"><?=date('M',mktime(0,0,0,$m,1,date('Y')));?></option>
				<?
			}
			?>
		</select>
		<select name="sDay" style="width:auto;">
			<option value=""></option>
			<?
			for($d=1;$d<32;$d++){
				?>
				<option<? echo ($_POST['sDay'] == $d ? ' selected' : ''); ?> value="<?=$d;?>"><?=$d;?></option>
				<?
			}
			?>
		</select>
		<select name="sYear" style="width:auto;">
			<option value=""></option>
			<?
			for($y=date('Y')+1;$y>date('Y')-5;$y--){
				?>
				<option<? echo ($_POST['sYear'] == $y ? ' selected' : ''); ?> value="<?=$y;?>"><?=$y;?></option>
				<?
			}
			?>
		</select>
		&nbsp;AND&nbsp;
		<select name="eMonth" style="width:auto;">
			<option value=""></option>
			<?
			for($m=1;$m<13;$m++){
				?>
				<option<? echo ($_POST['eMonth'] == $m ? ' selected' : ''); ?> value="<?=date('m',mktime(0,0,0,$m,1,date('Y')));?>"><?=date('M',mktime(0,0,0,$m,1,date('Y')));?></option>
				<?
			}
			?>
		</select>
		<select name="eDay" style="width:auto;">
			<option value=""></option>
			<?
			for($d=1;$d<32;$d++){
				?>
				<option<? echo ($_POST['eDay'] == $d ? ' selected' : ''); ?> value="<?=$d;?>"><?=$d;?></option>
				<?
			}
			?>
		</select>
		<select name="eYear" style="width:auto;">
			<option value=""></option>
			<?
			for($y=date('Y')+1;$y>date('Y')-5;$y--){
				?>
				<option<? echo ($_POST['eYear'] == $y ? ' selected' : ''); ?> value="<?=$y;?>"><?=$y;?></option>
				<?
			}
			?>
		</select>
	</p>
	<p class="pull-left" style="margin:0 40px 0 0;">
		<label><b>Member Type</b></label>
		<select name="mbr_type" style="width:auto;">
			<option value=""></option>
			<option<? echo ($_POST['mbr_type'] == 'Member Firm' ? ' selected' : ''); ?> value="Member Firm">Member Firm</option>
			<option<? echo ($_POST['mbr_type'] == 'Industry Partner' ? ' selected' : ''); ?> value="Industry Partner">Industry Partner</option>
		</select>
	
	</p>
	<p><input class="btn" type="submit" name="Submit" value="Run Report" /></p>
	</form>
</div>
<div style="padding:10px; margin:10px;">
	<div class="pull-right" style="font-size:18px; line-height:25px;"><a href="<?=DOMAIN_ROOT;?>backoffice/exportReport.php" target="_blank" style="color:#3f7a08; text-decoration:none;"><img src="<?=DOMAIN_ROOT;?>img/excel-icon.png" /> Export to Excel</a></div>
	<?
	echo '<b>'.$records.' member(s) found matching your query.</b><br />';
	?>
	<table class="table table-condensed table-striped">
		<tr class="info">
			<td><b>Mbr#</b></td>
			<td><b>Company</b></td>
			<td><b>Name</b></td>
			<td><b>Email</b></td>
			<td><b>Phone</b></td>
			<td><b>Date Joined</b></td>
			<td><b>Expires</b></td>
		</tr>
		<?
		for($r=0;$r<$records;$r++){
			$jDate = explode('-',$query[$r]['date_joined']);
			$eDate = explode('-',$query[$r]['date_expires']);
			?>
			<tr>
				<td><?=$query[$r]['mid'];?></td>
				<td><?=$query[$r]['mbr_company'];?></td>
				<td><?=$query[$r]['mbr_firstname'].' '.$query[$r]['mbr_lastname'];?></td>
				<td><?=$query[$r]['mbr_email'];?></td>
				<td><?=$query[$r]['mbr_phone'];?></td>
				<td><?=date('M j, Y',mktime(0,0,0,$jDate[1],$jDate[2],$jDate[0]));?></td>
				<td><?=date('M j, Y',mktime(0,0,0,$eDate[1],$eDate[2],$eDate[0]));?></td>
			</tr>
			<?
		}
		?>
	</table>
</div>