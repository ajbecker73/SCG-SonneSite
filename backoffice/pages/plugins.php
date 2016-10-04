<?
$boolErr = false;
$plugID = '';

if($_GET['id'] != ''){
	$ID = $_GET['id'];
}

if($_POST['Submit'] == "Activate"){
	$app->db->where('conf_key',$_POST['plug_name']);
	$app->db->delete('lmg_plugins');
	$ins = array(
		'conf_key' => $_POST['plug_name'],
		'conf_value' => 'true'
	);
	$app->db->insert('lmg_plugins',$ins);
}

if($_POST['Submit'] == "Deactivate"){
	$app->db->where('conf_key',$_POST['plug_name']);
	$app->db->delete('lmg_plugins');
	$ins = array(
		'conf_key' => $_POST['plug_name'],
		'conf_value' => 'false'
	);
	$app->db->insert('lmg_plugins',$ins);
}

?>
<h1>Plugins</h1>
<p>&nbsp;</p>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
		<h3>Installed Plugins</h3>
			<p>&nbsp;</p>
			<div class="data-grid">
				<?
					if ($handle = opendir(FILE_ROOT.'plugins/')) {
						while (false !== ($entry = readdir($handle))) {
							if ($entry != "." && $entry != "..") {
								$active = $app->db
									->where('conf_key',$entry)
									->get('lmg_plugins');
									$pCount = count($active);
								?>
								<div>
								<form action="" method="post" name="Plugins">
									<input type="hidden" name="plug_name" value="<?=$entry;?>" />
									<b><?=$entry;?></b>
									<input type="submit" name="Submit" class="btn btn-danger pull-right"<? echo($active[0]['conf_value'] == "false" || $pCount == 0 ? ' disabled' : ''); ?> value="Deactivate" />
									<input type="submit" name="Submit" class="btn btn-success pull-right"<? echo($active[0]['conf_value'] == "true" ? ' disabled' : ''); ?> value="Activate" style="margin-right:10px;" />
								</form>
								</div>
								<?
							}
						}
						closedir($handle);
					}
				?>
			</div>
		</div>
	</div>
</div>
