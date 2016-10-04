<?
include_once("db.php");
include_once(FILE_ROOT."inc/functions.php");
require_once(FILE_ROOT."cls/PHPMailer/PHPMailerAutoload.php");

class App {
	
	public $config;
	public $sid;
	public $db;
	public $members;
	public $cart;
	public $calendar;
	public $meta;
	public $pageName;
	public $debug = false;
	public $session_data = array();
	public $debugStr = '';
	public $states_arr = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa",  'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland", 'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma", 'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");

	// BUILT IN PHPMailer
	public $LMGMail;
	public function PonyExpress($to, $subject, $text ,$html, $from, $replyTo, $CC, $BCC, $attachment){
		$this->LMGMail = new PHPMailer;
		//BASIC MAIL SETTINGS
			$this->LMGMail->isHTML(true);
			$this->LMGMail->isSMTP();
			$this->LMGMail->SMTPDebug = 0;
			$this->LMGMail->Debugoutput = 'html';
			$this->LMGMail->Host = SMTP_HOST;
			$this->LMGMail->Port = SMTP_PORT;
			$this->LMGMail->SMTPAuth = true;
			$this->LMGMail->Username = SMTP_USERNAME;
			$this->LMGMail->Password = SMTP_PASSWORD;
		//MESSAGE SETTINGS
			if(!is_array($from)){//Set who the message is to be sent from
				$this->LMGMail->setFrom($from, COMPANY);
			}else{
				$this->LMGMail->setFrom($from[0], $from[1]);
			}
			if($replyTo != ''){
				if(!is_array($replyTo)){
					$this->LMGMail->addReplyTo($replyTo, '');
				}else{
					$this->LMGMail->addReplyTo($replyTo[0], $replyTo[1]);
				}
			}
			$this->LMGMail->Subject = $subject;//Set the subject line
			$this->LMGMail->Body = $html;
			$this->LMGMail->AltBody = $text;//FOr NON HTML RECEIVERS
			if($attachment != ''){
				$this->LMGMail->addAttachment($attachment);//Attach file
			}
		//Set who the message is to be sent to
			if(!is_array($to)){
				$this->LMGMail->addAddress($to, '');
			}else{
				for($e=0;$e<count($to);$e++){
					if(is_array($to[$e])){
						$this->LMGMail->addAddress($to[$e][0], $to[$e][1]);
					}else{
						$this->LMGMail->addAddress($to[$e], '');
					}
				}
			}
			if(!is_array($CC)){
				$this->LMGMail->addCC($CC, '');
			}else{
				for($e=0;$e<count($CC);$e++){
					if(is_array($CC[$e])){
						$this->LMGMail->addCC($CC[$e][0], $CC[$e][1]);
					}else{
						$this->LMGMail->addCC($CC[$e], '');
					}
				}
			}
			if(!is_array($BCC)){
				$this->LMGMail->addBCC($BCC, '');
			}else{
				for($e=0;$e<count($BCC);$e++){
					if(is_array($BCC[$e])){
						$this->LMGMail->addBCC($BCC[$e][0], $BCC[$e][1]);
					}else{
						$this->LMGMail->addBCC($BCC[$e], '');
					}
				}
			}
		//send the message, check for errors
			if (!$this->LMGMail->send()) {
				echo '<div class="alert alert-danger">'.$this->LMGMail->ErrorInfo.'</div>';
			}else{
				return true;
			}
	}
	////////////////////////////////////////////////////
	
	public function load(){
		
		// GET SESSION ID
			$this->sid = $_SESSION['sid'];
			if($this->sid == ''){
				$this->sid = md5(date("Ymdgis"));
			}
			$_SESSION['sid'] = $this->sid;
		
		// LOAD DATABASE & PLUGINS
			$this->db = new MysqliDB(DB_HOST ,DB_USER, DB_PASS, DB_NAME);
			$this->debugStr .= "database called<br>";
			
		// CONFIGURATION SETTINGS
			$this->config = $this->db->get('lmg_configuration');
			$configCount = count($this->config);
			for($configX=0;$configX<$configCount;$configX++){
				define($this->config[$configX]['conf_key'] , $this->config[$configX]['conf_value']);
			}
		
		// LOAD META DATA
			$this->meta = $this->db
				->where('page_name',$this->getPage())
				->get('lmg_pages');
		
		//LOGIN/LOGOUT
			if($_GET['action'] == 'alogin'){
				$this->adminLogin($_POST['username'],$_POST['password']);
			}
			if($_GET['action'] == 'alogout'){
				$this->adminLogout();
			}
			if($_GET['action'] == 'mlogin'){
				$this->memberLogin($_POST['username'],$_POST['password']);
			}
			if($_GET['action'] == 'mlogout'){
				$this->memberLogout();
			}
			if($_GET['action'] == 'addItem'){
				$opts = array();
				$pDiff = 0;
				foreach($_POST as $k => $v){
					if($k != 'qty' && $k != 'Submit'){
						$opts[$k] = $v;
						$op = $this->db
							->where('pid',$_GET['id'])
							->where('option_name',$k)
							->where('option_value',$v)
							->get('lmg_cart_product_options');
						$pDiff += $op[0]['option_price'];
					}
				}
				$this->addProduct($_GET['id'],$_POST['qty'],$_GET['price']+$pDiff,$_GET['title'],$_GET['taxable'],$opts);
			}
			if($_GET['action'] == 'removeItem'){
				$this->removeProduct($_GET['id']);
				$ct = 0;
				foreach($_SESSION['session_data']['cartProducts'] as $k => $v){
					$tempArr[$ct] = $_SESSION['session_data']['cartProducts'][$k];
					$ct++;
				}
				$_SESSION['session_data']['cartProducts'] = $tempArr;
			}
	}
	
	
	public function getPage(){
		
		$this->pageName = $_GET['url'];
		if($this->pageName == ''){
			$this->pageName = '';
		}
		return $this->pageName;
	}
	
	public function getNav(){
		$string = '';
		$this->db->where('parent','0');
		$this->db->orderBy('sort','ASC');
		$topNav = $this->db->get('lmg_navigation');
		$navCount = count($topNav);
		$string .= '<ul class="nav navbar-nav">';
		for($n=0;$n<$navCount;$n++){
			if($dropdown = $this->db->where('parent',$topNav[$n]['nid'])->orderBy('sort','ASC')->get('lmg_navigation')){
				$dropCount = count($dropdown);
				$string .= '<li class="dropdown">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">'.$topNav[$n]['name'].' <b class="glyphicon glyphicon-chevron-down" style="font-size:10px;"></b></a>
						<ul class="dropdown-menu">';
				for($dr=0;$dr<$dropCount;$dr++){
					$string .= '<li><a class="dd-link" href="'.$dropdown[$dr]['link'].'" target="'.$dropdown[$dr]['target'].'">'.$dropdown[$dr]['name'].'</a></li>';
				}
				$string .= '</ul></li>';
			}else{
				$string .= '<li><a href="'.$topNav[$n]['link'].'" target="'.$topNav[$n]['target'].'">'.$topNav[$n]['name'].'</a></li>';
			}
		}
		$string .= '</ul>';
		return $string;
	}
	
	public function getNav2(){
		$string = '';
		$nItems = 0;
		$this->db->where('parent','0');
		$this->db->orderBy('sort','ASC');
		$topNav = $this->db->get('lmg_navigation');
		$navCount = count($topNav);
		$string .= '<div class="row-fluid"><div class="span2"><ul class="nav">';
		for($n=0;$n<$navCount;$n++){
			if($dropdown = $this->db->where('parent',$topNav[$n]['nid'])->orderBy('sort','ASC')->get('lmg_navigation')){
				$dropCount = count($dropdown);
				$string .= '<li class=""><a href="'.$topNav[$n]['link'].'" class="dropdown-toggle">'.$topNav[$n]['name'].'</a></li>';
				$nItems ++;
				if($nItems == 5){
					$string .= '</ul></div><div class="span2"><ul class="nav">';
					$nItems = 0;
				}
				for($dr=0;$dr<$dropCount;$dr++){
					$string .= '<li class=""><a class="dd-link" href="'.$dropdown[$dr]['link'].'" target="'.$dropdown[$dr]['target'].'">'.$dropdown[$dr]['name'].'</a></li>';
				$nItems ++;
				if($nItems == 5){
					$string .= '</ul></div><div class="span2"><ul class="nav">';
					$nItems = 0;
				}
				}
			}else{
				$string .= '<li class=""><a href="'.$topNav[$n]['link'].'" target="'.$topNav[$n]['target'].'">'.$topNav[$n]['name'].'</a></li>';
				$nItems ++;
				if($nItems == 5){
					$string .= '</ul></div><div class="span2"><ul class="nav">';
					$nItems = 0;
				}
			}
		}
		$string .= '</ul></div></div>';
		return $string;
	}

	public function getFeaturedEvents($eventLimit){
		$params = array(date("Y-m-d"),'yes',$eventLimit);
		$upEvents = $this->db->rawQuery('SELECT * 
					FROM lmg_calendar 
					WHERE cal_startdate > ? 
					AND cal_featured = ? 
					ORDER BY cal_startdate ASC 
					LIMIT ?', $params);
			$evCount = count($upEvents);
			if($evCount<1){
				$evString = 'There are no featured events at this time';
				$evString .= '<a class="btn btn-primary" href="'.DOMAIN_ROOT.'calendar/calendar"><span class="icon-calendar"></span> Full Calendar</a>';
				return $evString;
			}else{
				for($ev=0;$ev<$evCount;$ev++){
					$evString .= '<h4>'.$upEvents[$ev]['cal_title']."</h4>";
					$evString .= writeDate($upEvents[$ev]['cal_startdate'],$upEvents[$ev]['cal_enddate'])."<br>";
					$evString .= '<a class="label label-info" href="'.DOMAIN_ROOT.'calendar/event-details?id='.$upEvents[$ev]['id'].'">Details</a>';
					if($upEvents[$ev]['cal_pdf'] != ''){
						$evString .= '<a class="label label-warning" href="'.DOMAIN_ROOT.'doc/uploads/'.$upEvents[$ev]['cal_pdf'].'">Flyer</a>';
					}
					if($upEvents[$ev]['cal_registration'] == 'yes'){
						$evString .= '<a class="label label-success" href="'.DOMAIN_ROOT.'calendar/registration?id='.$upEvents[$ev]['id'].'">Register</a>';
					}
					$evString .= '<hr />';
				}
				$evString .= '<a class="btn btn-primary" href="'.DOMAIN_ROOT.'calendar/calendar"><span class="icon-calendar"></span> Full Calendar</a>';
				return $evString;
			}
	}
	
	
	public function getNavGov(){
		$string = '';
		$this->db->where('government','yes');
		$this->db->orderBy('sort','ASC');
		$topNav = $this->db->get('lmg_navigation');
		$navCount = count($topNav);
		$string .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
		
		if($_SESSION['member']!= ''){                  
							
							$string .= '<tr><td class="mainlevel"><a class="mainlevel" href="member-login"><strong>Members Only</strong></a></td></tr>';						
					
                     
                        }
		for($n=0;$n<$navCount;$n++){
			
			
			if($dropdown = $this->db->where('parent',$topNav[$n]['nid'])->orderBy('sort','ASC')->get('lmg_navigation')){
				$dropCount = count($dropdown);
				$string .= '<tr><td class="mainlevel">
						<a href="'.$topNav[$n]['link'].'" class="mainlevel">'.$topNav[$n]['name'].' <b class="icon-chevron-down icon-white"></b></a>
						';
						
						
						
					
				for($dr=0;$dr<$dropCount;$dr++){
					
					
					$string .= '<tr><td class="mainlevel"><a class="mainlevel" href="'.$dropdown[$dr]['link'].'" target="'.$dropdown[$dr]['target'].'">'.$dropdown[$dr]['name'].'</a></td></tr>';
				}
				$string .= '</tr>';
			}else{
				
				$string .= '<td class="mainlevel"><a class="mainlevel" href="'.$topNav[$n]['link'].'" target="'.$topNav[$n]['target'].'">'.$topNav[$n]['name'].'</a></td></tr>';
			}
		}
		$string .= '</table>';
		return $string;
	}
	
public function getCoupons(){
			
		if($this->getPage() == ''){
			$pms = array('true','4');
			$boxes = 	$this->db->rawQuery('SELECT * FROM lmg_coupons WHERE featured = ? LIMIT ?',$pms);
		}else{
			$pms = array('1000');
			$boxes = 	$this->db->rawQuery('SELECT * FROM lmg_coupons LIMIT ?',$pms);
		}
		$boxCount = count($boxes);
		$string .= '<div class="row">';
		for($b=0;$b<$boxCount;$b++){
			$string .= '<div class="col-lg-3 col-md-3 col-sm-6 hidden-xs"><div class="coupon">
				<div class="couponHeading">'.stripslashes($boxes[$b]['couponHeading']).'</div>
				<div class="couponContent"><p>'.stripslashes($boxes[$b]['couponText']).'</p></div>
				<small>Cannot combine with other offers. Restrictions may apply.<br>Exp Date: ';
				if($boxes[$b]['couponExp'] != ''){
					$string .= stripslashes($boxes[$b]['couponExp']);
				} else {
					$string .= date('Y-n-d',strtotime('+2 week'));
				}
			$string .= '</small></div></div>';
		}
		$string .= '</div>';
		return $string;
	}
	
	public function getBoxes(){
		$string = '';
		if(OBITUARIES == "true"){
			$string = $this->getObits(10);
		}
		if(MEMBERS == "true"){
			if($_SESSION['session_data']['memberDetails']['mid'] != ""){
				$string .= '<div class="well">
					<h3>Member\'s Portal</h3>
						Welcome '.$_SESSION['session_data']['memberDetails']['firstname'].'
						<br>
						<a href="'.DOMAIN_ROOT.'members/member-portal">Dashboard</a><br>
						<a href="'.DOMAIN_ROOT.'members/member-directory">Member Directory</a><br>
						'.$this->getSecurePages().'
						<br>
						<a class="btn btn-danger" href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&action=mlogout"><span class="icon-lock"></span> logout</a>
				</div>';
			}else{
				//$string .= '<a class="btn btn-large btn-success" style="width:170px;" href="'.DOMAIN_ROOT.'members/sample">Sample ASA Document</a><br /><br />';
				$string .= '<div class="well">
					<h3>Member\'s Portal</h3>
					<form action="?'.$_SERVER['QUERY_STRING'].'&action=mlogin" method="post" name="MemberLogin">
					<label>Username</label>
						<input type="text" name="username" />
						<label>Password</label>
						<input type="password" name="password" />
						<br><br>
						<button class="btn btn-primary" type="submit" name="Submit"><span class="icon-lock"></span> LOGIN</button>
						<hr />Not a member yet?<br>
						<a class="btn btn-success" href="'.DOMAIN_ROOT.'members/join"><span class="icon-user"></span> JOIN TODAY</a>
					</form>
				</div>';
			}
		}
		if(CART == "true"){
			$string .= '<div class="well"><h3>Shopping Cart</h3>';
			$prods = $this->getCart();
			if(count($this->getCart()) > 0){
				$string .= '<table class="table table-striped table-condensed">';
				foreach($prods as $k => $v){
					$string .= '<tr><td><div class="cartBoxQty pull-left">'.$v['qty'].'</div></td><td>'.$v['title'].'</td></tr>';
				}
				$string .= '<tr><td colspan="2"><b>Subtotal: </b>$'.number_format($this->getSubtotal(),2).'</td></tr>';
				$string .='</table>';
				$string .= '<a class="btn" href="'.DOMAIN_ROOT.'cart/cart"><span class="icon-shopping-cart"></span> View Cart</a>';
			}else{
				$string .= 'There are no items in your cart.';
			}
			$string .= '<br><br><a class="btn btn-primary" href="'.DOMAIN_ROOT.'cart/store"><span class="icon-shopping-cart"></span> Store Front</a>';
			$string .= '</div>';
		}
		if(CALENDAR == "true"){
			$string .= '<div class="well"><h3>Featured Events</h3>';
			$string .= $this->getFeaturedEvents(5);
			$string .= '</div>';
		}
		$boxes = $this->db->get('lmg_boxes');
		$boxCount = count($boxes);
		for($b=0;$b<$boxCount;$b++){
				$string .= '<div class="well">
					<h3>'.stripslashes($boxes[$b]['box_title']).'</h3>
					'.stripslashes($boxes[$b]['box_contents']).'
				</div>';
		}
		return $string;
	}
	
	public function searchObits(){
		$evString = '<fieldset class="well">
			<legend>Search Obituaries</legend>
				<form class="form-search" action="'.DOMAIN_ROOT.'index.php?url=obituaries" method="post" name="ObirSearch2">
				  <div class="input-append">
				    <input value="'.$_POST['obitName'].'" name="obitName" type="text" style="width:125px;" class="search-query" placeholder="Search by name">
				    <button type="submit" class="btn">Go</button>
				  </div>
				</form>
				<h4>Search by date</h4>
				<form action="'.DOMAIN_ROOT.'index.php?url=obituaries" method="post" name="ObitSearch">
				<select name="month" style="width:auto;">
				<option value=""></option>';
				for($m=1;$m<13;$m++){
					$evString .= '<option value="'.date("m",mktime(0,0,0,$m,1,date("Y"))).'"';
					if($_POST['month'] == $m){
						$evString .= ' selected';
					}
					$evString .= '>'.date("F",mktime(0,0,0,$m,1,date("Y"))).'</option>';
				}
		$evString .= '</select>';
		$evString .= '<select name="year" style="width:auto;">
				<option value=""></option>';
				for($y=date("Y");$y>(date("Y")-11);$y--){
					$evString .= '<option value="'.$y.'"';
					if($_POST['year'] == $y){
						$evString .= ' selected';
					}
					$evString .= '>'.date("Y",mktime(0,0,0,1,1,$y)).'</option>';
				}
			$evString .= '</select><input type="submit" class="btn" name="Submit" value="Go" />
			</form></fieldset>';
		return $evString;
	}
	
	public function getObits($obitLimit){
		$params = array('yes',$obitLimit);
			$tot_obits = $this->db->rawQuery('SELECT * 
					FROM lmg_obituaries 
					WHERE publish = ? 
					ORDER BY deathdate DESC 
					LIMIT ?', $params);
				$evCount = count($tot_obits);
			if($evCount<1){
				$evString = '<div class="well">
					<h3>Recent Obituaries</h3>There are no Obituaries at this time
					</div>';
				return $evString;
			}else{
				$evString = '<div class="well">
					<h3>Recent Obituaries</h3>';
				for($t=0;$t<$evCount;$t++){
					$evString .= '<div class="clearfix obitRight" style="margin:10px 0;">';
					$evString .= '<h4 style="margin:0 0 5px 0; color:#6f7099;">'.stripslashes($tot_obits[$t]['firstname']).' '.stripslashes($tot_obits[$t]['lastname']).'<br />';
					$evString .= '<span style="color:#666; font-family:arial; font-size:12px;">'.writeDate($tot_obits[$t]['birthdate'],$tot_obits[$t]['deathdate']).'</span></h4>';
					if($tot_obits[$t]['photo'] != ''){
						$evString .= '<img src="'.DOMAIN_ROOT.'/img/uploads/'.$tot_obits[$t]['photo'].'" class="pull-left" style="max-width:50px; max-height:50px; margin:0 10px 5px 0;" />';
					}else{
						$evString .= '<img src="'.DOMAIN_ROOT.'/img/uploads/d4c34958c6a3d9829140ab4bfc76712c.jpg" class="pull-left" style="max-width:50px; max-height:50px; margin:0 10px 5px 0;" />';
					}
					if($tot_obits[$t]['specialtyicon'] != ''){
						$evString .= '<img src="'.DOMAIN_ROOT.'img/icons/'.$tot_obits[$t]['specialtyicon'].'" class="" style="max-width:30px; max-height:30px;" /><br />';
					}
					$evString .= substr(stripslashes(strip_tags($tot_obits[$t]['description'])),0,100).'<br /><a href="'.DOMAIN_ROOT.'obituaries/obituaries?id='.$tot_obits[$t]['id'].'"><img src="'.DOMAIN_ROOT.'tpl/'.THEME.'/img/view-obit.png" /></a></div><hr style="margin:0 5px;" />';
				}
				$evString .= '</div><a href="'.DOMAIN_ROOT.'obituaries/obituaries" class="btn btn-success">View All Obituaries</a></div>';
				return $evString;
			}
	}

	public function getSecurePages(){
		$this->db->where('page_secured','1');
		$pages = $this->db->get('lmg_pages');
		$pageCount = count($pages);
		$string = '';
		for($p=0;$p<$pageCount;$p++){
			$string .= '<a href="'.DOMAIN_ROOT.$pages[$p]['page_name'].'">'.$pages[$p]['page_name'].'</a><br>';
		}
		return $string;
	}
	
	public function getStateDropdown($name,$array,$active){
		$string = '';
		$string .= '<select name="'.$name.'">';
		foreach($array as $k => $v){
			$s = ($active == $k)? ' selected="selected"' : '';
			$string .= '<option value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";
		}
		$string .= '</select>';
		return $string;
	}
	
	public function adminLogin($usr,$pwd){
		if($admLI = $this->db
			->where('adm_username',$usr)
			->where('adm_password',encrypt($pwd,$usr))
			->where('adm_active','1')
			->get('lmg_administrators')){
				$_SESSION['session_data']['adminDetails'] = array(
					'aid' => $admLI[0]['aid'],
					'access' => $admLI[0]['adm_access'],
					'firstname' => $admLI[0]['adm_firstname'],
					'lastname' => $admLI[0]['adm_lastname'],
					'username' => $admLI[0]['adm_username'],
					'password' => $admLI[0]['adm_password']
				);
				header('location:'.DOMAIN_ROOT.'backoffice/?url=pages');
			}else{
				header('location:index.php?error=true');
			}
	}
	
	public function adminLogout(){
		unset($_SESSION['session_data']['adminDetails']);
				header('location:'.DOMAIN_ROOT);
	}
	
	public function memberLogin($usr,$pwd){
		if($mbrLI = $this->db
			->where('mbr_username',$usr)
			->where('mbr_password',encrypt($pwd,$usr))
			->get('lmg_users')){
				$_SESSION['session_data']['memberDetails'] = array();
				foreach($mbrLI[0] as $k => $v){
					$_SESSION['session_data']['memberDetails'][str_replace("mbr_","",$k)] = $v;
				}
				
		}else{
			header('location:index.php?error=true');
		}
	}
	
	public function array_equal($array1, $array2)
	{
	   $diff1 = array_diff($array1, $array2);
	   $diff2 = array_diff($array2, $array1);
	
	   return
	   (
		 (count($diff1) === 0) &&
		 (count($diff2) === 0)
	   );
	}

	public function memberLogout(){
		unset($_SESSION['session_data']['memberDetails']);
	}
	
	public function getCart(){
		return $_SESSION['session_data']['cartProducts'];
	}
	
	public function getSubtotal(){
		$sub = 0;
		if(count($this->getCart()) > 0){
			foreach($this->getCart() as $k => $v){
				$sub += $v['qty']*$v['price'].'<hr />';
			}
			return $sub;
		}else{
			return $sub;
		}
	}
	
	public function getTax(){
		$tTax = 0;
		$prods = $this->getCart();
		if(count($prods) > 0){
			foreach($prods as $k => $v){
				if($v['taxable'] == 'yes'){
					$tx = TAX_RATE*($v['price']*$v['qty']);
					$tTax += $tx;
				}
			}
			return $tTax;
		}else{
			return '0';
		}
	}
	
	public function addProduct($id,$qty,$price,$title,$taxable,$options){
		if(!is_array($_SESSION['session_data']['cartProducts'])){
			$_SESSION['session_data']['cartProducts'] = array();
		}
		$itms = count($_SESSION['session_data']['cartProducts']);
		$_SESSION['session_data']['cartProducts'][$itms] = array();
		$_SESSION['session_data']['cartProducts'][$itms]['id'] = $id;
		$_SESSION['session_data']['cartProducts'][$itms]['qty'] = $qty;
		$_SESSION['session_data']['cartProducts'][$itms]['price'] = $price;
		$_SESSION['session_data']['cartProducts'][$itms]['title'] = $title;
		$_SESSION['session_data']['cartProducts'][$itms]['taxable'] = $taxable;
		$_SESSION['session_data']['cartProducts'][$itms]['options'] = $options;
	}
	
	public function removeProduct($id){
		unset($_SESSION['session_data']['cartProducts'][$id]);
	}
	
	public function debugger(){
		
		$_SESSION['session_data']['debug'] = $_SESSION['session_data']['debug'];
		if($_GET['debug'] == 'on'){
			$_SESSION['session_data']['debug'] = true;
		}
		if($_GET['debug'] == 'off'){
			$_SESSION['session_data']['debug'] = false;
		}
		if($_SESSION['session_data']['debug']){
			echo '<div class="debugger" id="debugger"><pre style="background:none; color:#fff; margin:0; padding:0;">';
			echo '<div><a class="btn btn-mini" href="javascript:void()" onclick="hideDebug()">Hide</a>';
			echo '<a class="btn btn-mini" href="javascript:void()" onclick="showDebug()">Show</a>';
			echo '<h3>DEBUGGER</h3></div><hr />';
			echo '<p>'.$this->debugStr.'</p>';
			echo '<h3>CONFIGURATION SETTINGS</h3>';
			print_r($this->config);
			echo '<h3>POST DATA</h3>';
			print_r($_POST);
			echo '<h3>GET DATA</h3>';
			print_r($_GET);
			echo '<h3>SESSION DATA</h3>';
			print_r($_SESSION['session_data']);
			echo '<h3>SERVER VARIABLES</h3>';
			print_r($_SERVER);
			echo '</pre></div>';
		}
	}
//GET PAGE NAME FOR ACTION FORM ON MULTIPLE PAGES
	public function getName(){
		if(basename($_SERVER['PHP_SELF']) == 'Index.php'){
			echo DOMAIN_ROOT;
		} else {
			echo str_replace('index.php', '', DOMAIN_ROOT.basename($_SERVER['PHP_SELF']).$_GET['url']);
		}
	}
	
}

?>