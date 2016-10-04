<?
$obitID = $_GET['id'];
if($obitID != ''){
	$obit = $app->db->where('id',$obitID)->get('lmg_obituaries');
	?>
	<h1 style="line-height:30px;">
		<?=$obit[0]['firstname'].' '.$obit[0]['lastname'];?><br />
		<span style="color:#666; font-size:20px;"><?=writeDate($obit[0]['birthdate'],$obit[0]['deathdate']);?></span>
	</h1>
	<?
	if($obit[0]['photo'] != ''){
		?>
		<img class="pull-left" src="<?=DOMAIN_ROOT;?>img/uploads/<?=$obit[0]['photo'];?>" style="margin:0 20px 20px 0; max-height:200px; max-width:300px;" />
		<?
	}else{
		?>
		<img class="pull-left" src="<?=DOMAIN_ROOT;?>img/uploads/d4c34958c6a3d9829140ab4bfc76712c.jpg" style="margin:0 20px 20px 0; max-height:200px; max-width:300px;" />
		<?
	}
	if($obit[0]['specialtyicon'] != ''){
		echo '<img src="'.DOMAIN_ROOT.'img/icons/'.$obit[0]['specialtyicon'].'" class="" style="max-width:30px; max-height:30px;" /><br />';
	}
	?>
	<?=$obit[0]['description'];?>
	<div class="clearfix"></div>
	<?
	if($obit[0]['comments'] == 'yes'){
		?>
		<h2>Condolences</h2>
		<a class="btn btn-success" href="<?=DOMAIN_ROOT;?>send-condolence?id=<?=$obit[0]['id'];?>">Send Condolence</a>
		<br /><br />
		<?
		$conds = $app->db->
			where('obit',$obitID)->
			where('publish','yes')->
			order('date','DESC')->
			get('lmg_condolences');
			$conCt = count($conds);
		for($c=0;$c<$conCt;$c++){
			echo '<p>'.$conds[$c]['condolence'].'</p><b>By '.$conds[$c]['name'].'</b> - '.date("F d, Y",strtotime($conds[$c]['date'])).'<hr />';
		}
	}
	?>
	<?
}else{
	?>
	<h1>Obituaries</h1>
	<?
	if($_POST['month'] != '' && $_POST['year'] != ''){
		echo '<h3>'.date("F Y",mktime(0,0,0,$_POST['month'],1,$_POST['year'])).'</h3>';
		$sDate = $_POST['year'].'-'.$_POST['month'].'-01';
		$eDate =$_POST['year'].'-'.$_POST['month'].'-31';
		$params = array('yes',$sDate,$eDate);
		$tot_obits = $app->db
			->rawQuery('SELECT * FROM lmg_obituaries WHERE publish=? AND (deathdate BETWEEN ? AND ?) ORDER BY deathdate DESC',$params);
			$evCount = count($tot_obits);
	}else{
		if($_POST['obitName'] != ''){
			$params = array('yes','%'.$_POST['obitName'].'%','%'.$_POST['obitName'].'%');
			$tot_obits = $app->db
				->rawQuery('SELECT * FROM lmg_obituaries WHERE publish=? AND (firstname LIKE ? OR lastname LIKE ?) ORDER BY deathdate DESC',$params);
				$evCount = count($tot_obits);
		}else{
			echo '<h3>'.date("F Y",mktime(0,0,0,date("m")-1,1,date('Y'))).' to '.date("F Y",mktime(0,0,0,date("m"),31,date('Y'))).'</h3>';
			$sDate = date('Y').'-'.date("m",mktime(0,0,0,date("m")-1,1,date('Y'))).'-01';
			$eDate = date("Y").'-'.date("m",mktime(0,0,0,date("m")-1,1,date('Y'))).'-31';
			$params = array('yes',$sDate,$eDate);
			$tot_obits = $app->db
				->rawQuery('SELECT * FROM lmg_obituaries WHERE publish=? AND (deathdate BETWEEN ? and ?) ORDER BY deathdate DESC',$params);
				$evCount = count($tot_obits);
		}
	}
	if($evCount == 0){
		echo 'There are no obituaries matching your query.';
	}else{
		for($t=0;$t<$evCount;$t++){
			?>
			<div class="pull-left obitBox">
				<?
				if($tot_obits[$t]['photo'] != ''){
					?>
					<img class="pull-left" src="<?=DOMAIN_ROOT;?>img/uploads/<?=$tot_obits[$t]['photo'];?>" style="margin:0 20px 20px 0; max-height:130px; max-width:140px;" />
					<?
				}else{
					?>
					<img class="pull-left" src="<?=DOMAIN_ROOT;?>img/uploads/d4c34958c6a3d9829140ab4bfc76712c.jpg" style="margin:0 20px 20px 0; max-height:130px; max-width:140px;" />
					<?
				}
				?>
				<br />
				<?
					if($tot_obits[$t]['specialtyicon'] != ''){
						echo '<img src="'.DOMAIN_ROOT.'img/icons/'.$tot_obits[$t]['specialtyicon'].'" class="" style="max-width:30px; max-height:30px;" /><br />';
					}
				?>			
				<b><?=stripslashes($tot_obits[$t]['firstname']).' '.stripslashes($tot_obits[$t]['lastname']);?></b><br>
				<?=writeDate($tot_obits[$t]['birthdate'],$tot_obits[$t]['deathdate']);?><br>
				<a href="<?=DOMAIN_ROOT;?>obituaries?id=<?=$tot_obits[$t]['id'];?>">View Obituary</a>
			</div>
			<?
		}
	}
}
?>