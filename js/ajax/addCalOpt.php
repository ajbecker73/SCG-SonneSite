<?
	include_once('../../inc/config.php');
	include_once("../../inc/app_start.php");
	
	?>
	<div class="menuList" style="margin:10px 0 10px 0;">
		<strong>Type</strong>: <select name="cal_opt_type[]" style="width:auto;">
				<option value="text">text Input</option>
				<option value="textarea">description field</option>
				<option value="select">Select One</option>
			</select>
			<br />
			
		<strong>Name</strong>: <input type="text" name="cal_opt_name[]" style="width:auto;" />
			<br />

		<strong>Values</strong>:<br />(comma seperated list)<br />
		<textarea name="cal_opt_values[]" style="width:auto;" placeholder="leave empty for text fields"></textarea>
			<br />

		<strong>Price Difference</strong>: <input type="number" name="cal_opt_pricedifference[]" min="-9999.99" max="9999.99" step=".01" style="width:auto;" />
			<br />

	</div>
	<?
	
	include_once("../../inc/app_end.php");
?>