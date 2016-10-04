<?
$eid = $_GET['id'];
$evt = $app->db
	->where('id',$eid)
	->get('lmg_calendar');
?>
<h1><?=$evt[0]['cal_title'];?></h1>
<h3><?=writeDate($evt[0]['cal_startdate'],$evt[0]['cal_enddate']);?></h3>
<p class="pull-right">
<b>Member Price</b>: $<?=number_format($evt[0]['cal_memberprice'],2);?><br>
<b>Non-Member Price</b>: $<?=number_format($evt[0]['cal_nonmemberprice'],2);?><br>
<?
if($evt[0]['cal_pdf'] != ""){
	?>
	<br><a class="btn btn-warning" href="<?=DOMAIN_ROOT;?>pdf/<?=$evt[0]['cal_pdf'];?>" target="_blank">Flyer</a>
	<?
}

if($evt[0]['cal_registration'] == "yes"){
	?>
	<br><a class="btn btn-success" href="<?=DOMAIN_ROOT;?>calendar/registration?id=<?=$evt[0]['id'];?>">Register</a>
	<?
}

?>
</p>
<p><?=$evt[0]['cal_description'];?></p>
