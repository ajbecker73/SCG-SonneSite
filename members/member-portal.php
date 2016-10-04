<h1>Members Dashboard</h1>
<?
if($_POST['Submit'] == "Save Changes"){
	
	$boolErr = false;
	$passErr = false;
	
	foreach($_POST as $pK => $pV){
		if($pK != "Submit" && $pK != "mbr_password1" && $pK != "mbr_password2"){
			$Member{$pK} = $pV;
			if($pK == "mbr_phone"){
				$Member{$pK} = formatPhone($Member{$pK});
			}
			if($Member{$pK} == ""){
				$boolErr = true;
			}
		}
	}
	
	if($_POST['mbr_password1'] != $_POST['mbr_password2']){
		$boolErr = true;
		$passErr = true;
	}
	
	if(!$boolErr && !$passErr){
		$upData = array();
		foreach($_POST as $pK => $pV){
			if($pK != "Submit" && $pK != "mbr_password1" && $pK != "mbr_password2"){
				if($pK == "phone"){
					$_SESSION['session_data']['memberDetails'][$pK] = formatPhone($pV);
					$upData[$pK] = formatPhone($pV);
				}else{
					$_SESSION['session_data']['memberDetails'][$pK] = $pV;
					$upData[$pK] = $pV;
				}
			}
		}
		if($_POST['mbr_password1'] != "" && $_POST['mbr_password1'] == $_POST['mbr_password2']){
			$_SESSION['session_data']['memberDetails']['password'] = encrypt($_POST['password1'],$_POST['username']);
			$upData['mbr_password'] = encrypt($_POST['mbr_password1'],$_POST['mbr_username']);
		}
		$app->db
			->where('mid',$_SESSION['session_data']['memberDetails']['mid'])
			->update('lmg_users',$upData);
		?>
		<div class="alert alert-sucess">Profile Successfully Updated.</div>
		<?
	}else{
		if($boolErr){
			?>
			<div class="alert alert-error">Error, Please fill in all fields.</div>
			<?
			if($passErr){
				?>
				<div class="alert alert-error">Passwords did not match.</div>
				<?
			}
		}
	}
}
?>
<p>&nbsp;</p>
<table class="table">
	<tr>
		<td>
			<form action="" method="post" name="MemberProfile">
			<?
			$getMember = $app->db
				->where('mid',$_SESSION['session_data']['memberDetails']['mid'])
				->get('lmg_users');
			$fCount = count($getMember);
			for($f=0;$f<$fCount;$f++){
				$record = $getMember[0];
				foreach($record as $fK => $fV){
					if($fK != 'mid' && $fK != 'last_updated' && $fK != 'mbr_active'){
						//echo ($fk == 'mbr_firstname' ? '<div class="pull-left">' : '');
						switch(str_replace("mbr_","",$fK)){
							case 'state':
								?>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<?=$app->getStateDropdown($fK,$app->states_arr,$_POST['mbr_state']);?>
								<?
								break;
								
							case 'password':
								?>
								<br /><br /><b class="label label-info">Leave passwords blank unless you wish to change your password</b>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<input autocomplete="off" id="pass1" type="password" name="<?=$fK;?>1" />
								<a id="passBtn" class="btn">Generate Password</a> <span id="genPass" class="alert blue" style="letter-spacing:1px;"></span>
								<label><b>Confirm Password</b></label>
								<input autocomplete="off" id="pass2" type="password" name="<?=$fK;?>2" />
								<?
								break;
								
							case 'phone':
								?>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<input type="text" name="<?=$fK;?>" value="<? echo ($_POST['Submit'] == "Save Changes" ? $Member{$fK} : $fV);?>" />
								</td><td>
								<?
								break;
								
							default:
								?>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<input type="text" name="<?=$fK;?>" value="<? echo ($_POST['Submit'] == "Save Changes" ? $Member{$fK} : $fV);?>" />
								<?
								break;
								
						}
						//echo ($fk == 'mbr_phone' ? '</td><td>' : '');
					}
				}
			}
			?>
			<p>&nbsp;</p>
			<input type="submit" name="Submit" value="Save Changes" class="btn btn-primary" />
			</form>
		</td>
	</tr>
</table>