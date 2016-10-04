<?
	include_once('../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');

	$expFileName = "custom_member_report";
	$expFileName .= "_".date("M_d_Y");
	header("Content-type: application/ms-excel");
	header("Content-Disposition: attachment;filename=".$expFileName.".xls");
	
	$cols = $app->db->rawQuery('SHOW columns FROM lmg_registrations');
	$cCT = count($cols);

	$query = $app->db->rawQuery($_SESSION['mSearchQL'],$_SESSION['mSearchParams']);
	$qCT = count($query);
	
	echo '<html><head>
			<link href="'.DOMAIN_ROOT.'css/reset.css" rel="stylesheet" />
			</head><body>
			<table class="table table-condensed table-striped"><tr class="info" style="border-bottom:1px solid #ccc;">';
			for($f=0;$f<$cCT;$f++){
				echo '<td style="border-right:1px solid #ccc;"><b>'.str_replace("reg_","",$cols[$f]['Field']).'</b></td>';
			}
	echo '</tr>';
	for($q=0;$q<$qCT;$q++){
		echo '<tr>';
		foreach($query[$q] as $k => $v){
			?>
				<td style="border-right:1px solid #ccc; border-bottom:1px solid #ccc; vertical-align:top; text-align:left;">
				<?
				if($k == 'reg_attendees'){
					$v = unserialize($v);
					$ct = count($v[0]);
					for($c=0;$c<$ct;$c++){
						echo $v[0][$c].' '.$v[1][$c].'<br />';
					}
				}else{
					echo $v;
				}
				?>
				</td>
			<?
		}
		echo '</tr>';
	}
	echo '</table></body></html>';
	
	include_once(FILE_ROOT.'inc/app_end.php');
?>