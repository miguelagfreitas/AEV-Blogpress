<?php


class User extends Controller {
	public function view($f3) {
		$userid = $f3->get('PARAMS.3');
		$u = $this->Model->Users->fetch($userid);

		$articles = $this->Model->Posts->fetchAll(array('user_id' => $userid));
		$comments = $this->Model->Comments->fetchAll(array('user_id' => $userid));

		$f3->set('u',$u);
		$f3->set('articles',$articles);
		$f3->set('comments',$comments);
	}

	public function add($f3) {
		if($this->request->is('post')) {
			extract($this->request->data);
			$check = $this->Model->Users->fetch(array('username' => $username));
			if (!empty($check)) {
				\StatusMessage::add('User already exists','danger');
			} else if($password != $password2) {
				\StatusMessage::add('Passwords must match','danger');
			} else {
				$user = $this->Model->Users;
				$user->copyfrom('POST');
				$user->created = mydate();
				$user->bio = '';
				$user->level = 1;
				$user->setPassword($password);
				 
				if(empty($displayname)) {
					$user->displayname = $user->username;
				}

				$user->save();	
				StatusMessage::add('Registration complete','success');
				return $f3->reroute('/user/login');
			}
		}
	}

	public function login($f3) {
		/** YOU MAY NOT CHANGE THIS FUNCTION - Make any changes in Auth->checkLogin, Auth->login and afterLogin() */
		if ($this->request->is('post')) {
	
			//Check for debug mode
			$settings = $this->Model->Settings;
			$debug = $settings->getSetting('debug');

			//Either allow log in with checked and approved login, or debug mode login
			list($username,$password) = array($this->request->data['username'],$this->request->data['password']);
			if (
				($this->Auth->checkLogin($username,$password,$this->request,$debug) && ($this->Auth->login($username,$password))) ||
				($debug && $this->Auth->debugLogin($username))) {
					$this->afterLogin($f3);
			} else {
				\StatusMessage::add('Invalid username, password or captcha','danger');
			}
		}		
	}

	/* Handle after logging in */
	private function afterLogin($f3) {
				StatusMessage::add('Logged in succesfully','success');

				//Redirect to where they came from
				if(isset($_GET['from'])) {
					// Check if he came from our own page
					// whitelisted places to login
					$whitelist_urls = array(
						"/^\/$/",
						"/^\/blog\/search$/",
						"/^\/contact$/",
						"/^\/page\/display\/*$/"
					);
					$from = $_GET['from'];	
					$count = count($whitelist_urls);
					for($i=0;$i<$count;$i++){
						if(preg_match_all($whitelist_urls[$i],$from)){
							\StatusMessage::add("Valid redirect",'success');
							$f3->reroute($from);
						}else{
							\StatusMessage::add("Invalid redirect",'success');
							$f3->reroute('/');
						}
					}
				} else {
					$f3->reroute('/');	
				}
	}

	public function logout($f3) {
		$this->Auth->logout();
		\StatusMessage::add('Logged out succesfully','success');
		$f3->reroute('/');	
	}


	public function profile($f3) {	
		$id = $this->Auth->user('id');
		extract($this->request->data);
		$u = $this->Model->Users->fetch($id);
		$oldpass = $u->password;
		if($this->request->is('post')) {
			$u->copyfrom('POST');
			if(empty($u->password)) {
				 $u->password = $oldpass; 
			} else {
				$u->setPassword($u->password);
			}

			//Handle avatar upload
			$allowed = array('png','jpg');
			// Test the file extension
			// Replace special characters with ""
			$_FILES['avatar']['tmp_name'] = preg_replace("/[;:><%$&!@#]/","",$_FILES['avatar']['tmp_name']);
			$filename = $_FILES['avatar']['name'];
			$tmp = explode(".",$filename);
			$tmp = array_slice($tmp,1);
			// returns png if file was x.png but returns php.png if file was x.php.png
			$file_extension = implode(".",$tmp);

			if(!in_array($file_extension,$allowed)){
				\StatusMessage::add("Invalid File Extension",'danger');
				\StatusMessage::add("Avatar was reset",'danger');
				$u->avatar = '';
				$u->save();
				return $f3->reroute('/user/profile');
			}
			else if(isset($_FILES['avatar']) && 
				isset($_FILES['avatar']['tmp_name']) && 
				!empty($_FILES['avatar']['tmp_name'])){
				$url = File::Upload($_FILES['avatar']);
				$u->avatar = $url;
			} else if(isset($reset)) {
				$u->avatar = '';
			}

			$u->save();
			\StatusMessage::add('Profile updated succesfully','success');
			return $f3->reroute('/user/profile');
		}			
		$_POST = $u->cast();
		$f3->set('u',$u);
	}

}
?>
