<div class="homeForm">
	<form action="<?php echo $app->getName();?>" method="post" name="contact form">
	<?php
	if(isset($_REQUEST['Send'])){
		
		$nameErr = false;
		$keyErr = false;
		
		$name = $_POST['name'];
		$phone = $_POST['phone'];

		
		if($name == ""){
			$message = '<div class="alert alert-danger">Please Fill In Your Name</div';
		}
		if($_POST['verify_num'] != $_POST['verify_them']) { 
			$message = '<div class="alert alert-danger">Security Codes Must Match!</div>';
		}
		if($keyErr == false && $nameErr == false){
			$subject = 'New message from '.$name;
			$body =  'Name: '.$name.'<br>
					 Phone: '.$phone.'<br>';
									
			LMGmail(array(COMPANY_EMAIL,'messages@lmgnow.com'), $subject, $body ,$body, COMPANY_EMAIL);
			echo '<!--REDIRECTING STARTS-->
				<link href="'.DOMAIN_ROOT.'thank-you" rel="canonical" /><noscript>
				<meta http-equiv="refresh" content="0;URL='.DOMAIN_ROOT.'thank-you">
				</noscript><script type="text/javascript">
					var url = "'.DOMAIN_ROOT.'thank-you";
				
					// IE8 and lower fix
					if (navigator.userAgent.match(/MSIE\s(?!9.0)/))
					{
						var referLink = document.createElement("a");
						referLink.href = url;
						document.body.appendChild(referLink);
						referLink.click();
					}
				
					// All other browsers
					else { window.location.replace(url); }
				</script>
				<!--REDIRECTING ENDS-->';
		}else{
			echo '<div class="alert alert-danger">Please fix errors</div>';
		}
		
	}
	?>
	<? echo $message;?>
		<label>Name: </label>
		<input type="text" class="form-control" name="name" value="<?php echo $_POST['name'];?>" placeholder="Required" required />
		<label>Phone: </label>
		<input type="text" class="form-control" name="phone" value="<?php echo $_POST['phone'];?>" placeholder="Required" required />
		
		<?
		$secCode = gen_pass(5)
		?>
		<input type="hidden" name="verify_num" value="<?=$secCode;?>" />
		<label class="control-label">Security Code: <span style="font-size:20px; font-weight:normal;"><?=$secCode;?></span></label>
		<input class="form-control" type="text" name="verify_them" value="" /><br />
	<input type="submit" value="Send" name="Send" class="btn btn-warning pull-right" />
	<div class="clearfix">&nbsp;</div>
	</form>
</div>