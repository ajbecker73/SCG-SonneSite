<?
class Members{
	
	public function load(){
		
		//MEMBER LOGIN/LOGOUT
			if($_GET['action'] == 'mlogin'){
				$this->memberLogin($_POST['username'],$_POST['password']);
			}
			if($_GET['action'] == 'mlogout'){
				$this->memberLogout();
			}
	}
	
	public function getSecurePages(){
		$this->db->where('page_secured','1');
		$pages = $this->db->get('lmg_pages');
		$pageCount = count($pages);
		$string = '';
		for($p=0;$p<$pageCount;$p++){
			$string .= '<a href="'.DOMAIN_ROOT.$pages[$p]['page_name'].'">'.$pages[$p]['page_name'].'</a><br />';
		}
		return $string;
	}
	
	public function memberLogin($usr,$pwd){
		if($mbrLI = $db
			->where('mbr_username',$usr)
			->where('mbr_password',encrypt($pwd,$usr))
			->get('lmg_members')){
				$_SESSION['session_data']['memberDetails'] = array(
					'mid' => $mbrLI[0]['mid'],
					'firstname' => $mbrLI[0]['mbr_firstname'],
					'lastname' => $mbrLI[0]['mbr_lastname'],
					'email' => $mbrLI[0]['mbr_email'],
					'phone' => $mbrLI[0]['mbr_phone'],
					'address' => $mbrLI[0]['mbr_address'],
					'city' => $mbrLI[0]['mbr_city'],
					'state' => $mbrLI[0]['mbr_state'],
					'zip' => $mbrLI[0]['mbr_zip'],
					'username' => $mbrLI[0]['mbr_username'],
					'password' => $mbrLI[0]['mbr_password']
				);
				header('location:'.DOMAIN_ROOT.'/member-portal');
		}else{
			header('location:index.php?error=true');
		}
	}
	
	public function memberLogout(){
		unset($_SESSION['session_data']['memberDetails']);
				header('location:index.php');
	}

}
?>