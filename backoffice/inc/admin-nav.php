<?
if(strpos($_SERVER['SCRIPT_FILENAME'],"backoffice")){
	?>
	<ul class="nav navbar-nav pull-right">
		<li><a href="<?=DOMAIN_ROOT;?>">Back to Site</a></li>
	</ul>
	<?	
}
?>
<ul class="nav navbar-nav">
	<? if($_SESSION['session_data']['adminDetails']['access'] == "Administrator"){ ?>
		<li><a href="<?=DOMAIN_ROOT;?>backoffice/?url=settings" target="_self">Site Settings</a></li>
	<? } ?>
	
	<li class="dropdown">
		<a href="javascript:void()" class="dropdown-toggle">Content<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="<?=DOMAIN_ROOT;?>backoffice/?url=menu" target="_self">Menu</a></li>
			<li><a href="<?=DOMAIN_ROOT;?>backoffice/?url=pages" target="_self">Pages</a></li>
			<li><a href="<?=DOMAIN_ROOT;?>backoffice/?url=boxes" target="_self">Boxes</a></li>
		</ul>
	</li>
	
	<? if(CART == "true"){ ?>
	<li class="dropdown">
		<a href="javascript:void()" class="dropdown-toggle">Store<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=categories" target="_self">Categories</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=products" target="_self">Products</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=orders" target="_self">Orders</a></li>
		</ul>
	</li>
	<? } ?>
	
	<? if(CALENDAR == "true"){ ?>
	<li class="dropdown">
		<a href="javascript:void()" class="dropdown-toggle">Calendar<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=calendar" target="_self">Events</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=searchRegistrations" target="_self">Registration Reports</a></li>
		</ul>
	</li>
	<? } ?>
	
	<? if(GALLERY == "true"){ ?>
	<li><a href="<?=DOMAIN_ROOT;?>backoffice/?url=gallery" target="_self">Photo Gallery</a></li>
	<? } ?>
	
	<? if(MEMBERS == "true"){ ?>
	<li class="dropdown">
		<a href="javascript:void()" class="dropdown-toggle">Members<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=members" target="_self">Current Members</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=users" target="_self">Current Users</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=prospects" target="_self">Member Prospects</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=searchMembers" target="_self">Members Reports</a></li>
		</ul>
	</li>
	<? } ?>
	
	<? if(OBITUARIES == "true"){ ?>
	<li><a href="<?=DOMAIN_ROOT;?>backoffice/?url=obituaries" target="_self">Obituaries</a></li>
	<? } ?>
	
	<? if(SCHEDULER == "true"){ ?>
	<li class="dropdown">
		<a href="javascript:void()" class="dropdown-toggle">Scheduler<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=scheduler" target="_self">Appointments</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=scheduler2" target="_self">Hours Available</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=scheduler3" target="_self">Services</a></li>
			<li><a class="dd-link" href="<?=DOMAIN_ROOT;?>backoffice/?url=employees" target="_self">Employees</a></li>
		</ul>
	</li>
	<? } ?>
	
	<? if(SLIDESHOW == "true"){ ?>
	<li><a href="<?=DOMAIN_ROOT;?>backoffice/?url=slideshow" target="_self">Slideshow</a></li>
	<? } ?>
	
	<li><a href="?action=alogout" target="_self">Logout</a></li>
</ul>
