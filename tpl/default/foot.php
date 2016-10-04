	<footer id="footer">
		<div class="footer">
			Copyright &copy; <?= date("Y").", ".COMPANY; ?>. All rights reserved. <br />
			Powered By: <a href="http://www.lmgnow.com" target="_blank">Lifestyles Media Group, LLC.</a>
			&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=DOMAIN_ROOT;?>backoffice/?url=pages">BackOffice</a> | <a href="<?=DOMAIN_ROOT;?>sitemap">Site Map</a>
		</div>
	</footer>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<script src="<?=DOMAIN_ROOT;?>js/jquery.fancybox.js?v=2.1.3"></script>
	<script src="<?=DOMAIN_ROOT;?>js/flot/jquery.flot.js"></script>
	<script src="<?=DOMAIN_ROOT;?>js/plugins.js"></script>
	<script src="<?=DOMAIN_ROOT;?>js/jquery-ui-1.8.22.custom.min.js"></script>
	<script src="<?=DOMAIN_ROOT;?>js/jquery.timepicker.js"></script>
	<script src="<?=DOMAIN_ROOT;?>js/jquery.mousewheel-3.0.6.pack.js"></script>
	<script src="<?=DOMAIN_ROOT;?>js/garand-sticky/jquery.sticky.js"></script> 
	<script src="<?=DOMAIN_ROOT;?>js/ckeditor/ckeditor.js"></script>
	<script src="<?=DOMAIN_ROOT;?>js/global.js"></script>
	<?
	if($app->getPage() == "" && SLIDESHOW == "true"){
		?>
		<script type="text/javascript">
			$('.carousel').carousel()
		</script>
	<?
	}
	?>
<!--[if lt IE 9]>
<script>
$('[placeholder]').focus(function()
{
  var input = $(this);
  if(input.val() == input.attr('placeholder'))
  {
    input.val('');
    input.removeClass('placeholder');
  }
}).blur(function()
{
  var input = $(this);
  if(input.val() == '' || input.val() == input.attr('placeholder')) {
    input.addClass('placeholder');
    input.val(input.attr('placeholder'));
}
}).blur();
</script>
<![endif]--> 
<? 
	$app->debugger(); 
?>
</div>
</body>
</html>
