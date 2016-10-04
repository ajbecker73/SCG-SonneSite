<?
if($_POST['Submit'] == "Save Changes"){
	$cTotal = count($_POST);
		$app->db->delete('lmg_configuration');
		foreach($_POST as $k => $v){
			if($k != 'Submit'){
				$insertData = array(
					'conf_key' => $k,
					'conf_value' => $v
				);
				$app->db->insert('lmg_configuration',$insertData);
			}
		}
	header('location:'.DOMAIN_ROOT.'backoffice/?url=settings');
}
?>
<div class="row">
	<div class="col-lg-12 col-sm-12 col-12">
		<h1>Site Settings</h1>
	</div>
</div>
<!--<div class="container">
	<div class="row">
		<div class="span12">
			<h3>Site Visitors Last 30 Days</h3>
			<div id="siteVisitors" style="width:930px;height:200px;margin-bottom:20px;"></div>
		</div>
	</div>
</div>-->
<form action="" method="post" name="SiteSettings">
	<div class="row">
		<div class="col-lg-6 col-sm-6">
			<h3>Basic Settings</h3>
			<label style="display:inline-block;">Maintenance Mode</label>
			<input type="radio" name="OFFLINE" value="false"<? echo(OFFLINE == 'false' ? ' checked' : ''); ?> />No
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="OFFLINE" value="true"<? echo(OFFLINE == 'true' ? ' checked' : ''); ?> />Yes
			<br><br>
			<label>Company Name</label>
			<input class="form-control" style="width:400px;" type="text" name="COMPANY" value="<?=COMPANY;?>" />
			<br>
			<label>Company Address</label>
			<input class="form-control" style="width:400px;" type="text" name="COMPANY_ADDRESS" value="<?=COMPANY_ADDRESS;?>" />
			<br>
			<label>Company Phone</label>
			<input class="form-control" style="width:400px;" type="text" name="COMPANY_PHONE" value="<?=COMPANY_PHONE;?>" />
			<br>
			<label>Company Email</label>
			<input class="form-control" style="width:400px;" type="text" name="COMPANY_EMAIL" value="<?=COMPANY_EMAIL;?>" />
			<br>
			<h3>Add Ons</h3>
			<label style="display:inline-block;">Home Page Slideshow</label>
			<input type="radio" name="SLIDESHOW" value="false"<? echo(SLIDESHOW == 'false' ? ' checked' : ''); ?> />No
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="SLIDESHOW" value="true"<? echo(SLIDESHOW == 'true' ? ' checked' : ''); ?> />Yes
			<br><br>
			<label style="display:inline-block;">Members Portal</label>
			<input type="radio" name="MEMBERS" value="false"<? echo(MEMBERS == 'false' ? ' checked' : ''); ?> />No
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="MEMBERS" value="true"<? echo(MEMBERS == 'true' ? ' checked' : ''); ?> />Yes
			<br><br>
			<label style="display:inline-block;">Events Calendar</label>
			<input type="radio" name="CALENDAR" value="false"<? echo(CALENDAR == 'false' ? ' checked' : ''); ?> />No
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="CALENDAR" value="true"<? echo(CALENDAR == 'true' ? ' checked' : ''); ?> />Yes
			<br><br>
			<label style="display:inline-block;">Appointment Scheduler</label>
			<input type="radio" name="SCHEDULER" value="false"<? echo(SCHEDULER == 'false' ? ' checked' : ''); ?> />No
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="SCHEDULER" value="true"<? echo(SCHEDULER == 'true' ? ' checked' : ''); ?> />Yes
			<br><br>
			<label style="display:inline-block;">Photo Gallery</label>
			<input type="radio" name="GALLERY" value="false"<? echo(GALLERY == 'false' ? ' checked' : ''); ?> />No
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="GALLERY" value="true"<? echo(GALLERY == 'true' ? ' checked' : ''); ?> />Yes
			<br><br>
			<label style="display:inline-block;">Online Store</label>
			<input type="radio" name="CART" value="false"<? echo(CART == 'false' ? ' checked' : ''); ?> />No
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="CART" value="true"<? echo(CART == 'true' ? ' checked' : ''); ?> />Yes
			<br><br>
			<label style="display:inline-block;">Obituaries</label>
			<input type="radio" name="OBITUARIES" value="false"<? echo(OBITUARIES == 'false' ? ' checked' : ''); ?> />No
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="OBITUARIES" value="true"<? echo(OBITUARIES == 'true' ? ' checked' : ''); ?> />Yes
		</div>
		<div class="col-lg-6 col-sm-6">
			<h3>SEO / Social Networking</h3>
			<label>Google Verification Code</label>
			<input class="form-control" style="width:400px;" type="text" name="GOOGLE" value="<?=stripslashes(htmlspecialchars(GOOGLE));?>" />
			<br>
			<label>Yahoo Verification Code</label>
			<input class="form-control" style="width:400px;" type="text" name="YAHOO" value="<?=stripslashes(htmlspecialchars(YAHOO));?>" />
			<br>
			<label>Bing Verification Code</label>
			<input class="form-control" style="width:400px;" type="text" name="BING" value="<?=stripslashes(htmlspecialchars(BING));?>" />
		
			<h3>Payment Processor</h3>
			<label><input type="radio" name="MERCHANT" value=""<? echo(MERCHANT == '' ? ' checked' : ''); ?> /> NONE</label>
			<br />
			<label><input type="radio" name="MERCHANT" value="paypal"<? echo(MERCHANT == 'paypal' ? ' checked' : ''); ?> /> Paypal</label>
			<br />
<input placeholder="API Username" class="form-control" style="width:400px;" type="text" name="PP_UserName" value="<?=PP_UserName;?>" /><br />
			<input placeholder="API Password" class="form-control" style="width:400px;" type="text" name="PP_Password" value="<?=PP_Password;?>" /><br />
			<input placeholder="API Signature" class="form-control" style="width:400px;" type="text" name="PP_Signature" value="<?=PP_Signature;?>" /><br />
			<br />
			<label><input type="radio" name="MERCHANT" value="authorize"<? echo(MERCHANT == 'authorize' ? ' checked' : ''); ?> /> Authorize.net
			</label><br />
			<input placeholder="API Transaction Key" class="form-control" style="width:400px;" type="text" name="AN_TransactionKey" value="<?=AN_TransactionKey;?>" /><br />
			<input placeholder="API Username" class="form-control" style="width:400px;" type="text" name="AN_Password" value="<?=AN_Password;?>" /><br />
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
		<br>
			<h3>Theme</h3>
			<?
			if ($handle = opendir(FILE_ROOT.'tpl/')) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						?>
						<div class="theme-thumb pull-left<? echo(THEME == $entry ? ' theme-thumb-active' : ''); ?>">
							<b><?=$entry;?></b><br>
							<img src="<?=DOMAIN_ROOT.'tpl/'.$entry.'/thumb.png';?>" /><br>
							<input type="radio" name="THEME" value="<?=$entry;?>"<? echo(THEME == $entry ? ' checked' : ''); ?> />
						</div>
						<?
					}
				}
				closedir($handle);
			}
			?>
		</div>
	</div>
	<input type="submit" name="Submit" value="Save Changes" class="btn pull-right" />
</form>