<?php

	class AuthHelper {
		
		/** Construct a new Auth helper */
		public function __construct($controller) {
			$this->controller = $controller;
		}

		/** Attempt to resume a previously logged in session if one exists */
		public function resume() {
			$f3=Base::instance();				
			$db = $this->controller->db;

			//Ignore if already running session	
			if($f3->exists('SESSION.user')) return;

			//Log user back in from cookie
			if($f3->exists('COOKIE.BlogPress_User')) {	
				$cookie = $_COOKIE["BlogPress_User"];
				$data = $db->safeQuery("SELECT * FROM BlogPress_Cookie WHERE BlogPress_Cookie=?",$cookie);
				$expire_date = $data[0]["maxlifetime"];
				//Checking if the cookie is still valid
				if(time()<$expire_date){
					$user = $this->controller->Model->Users->fetch(array('id' => $data[0]["user_id"]));
					$user = $user->cast();
					$this->forceLogin($user);
				}
			}
		}		

		/** Perform any checks before starting login */
		public function checkLogin($username,$password,$request,$debug) {
			return true;
			if($request->data['captcha']==$_SESSION['captcha_code']){
				return true;
			}
			$f3=Base::instance();						
			$db = $this->controller->db;
			$results = $db->query("SELECT * FROM `users` WHERE `username`='$username'");
			return false;
		}


		public function login($username,$password) {
			$f3=Base::instance();						
			$db = $this->controller->db;
			$results = $db->query("SELECT * FROM `users` WHERE `username`='$username'");
			if (!empty($results)) {
				# Comparing the hash of the inserted password with the one in the database
				# Obtaining the hash
				$user_salt = base64_decode($results[0]["salt"]);
				$inserted_pass = hash_pbkdf2("sha256",$password,$user_salt,50,20);
				$saved_pass = $results[0]['password'];
				# Actual comparation
				if($inserted_pass == $saved_pass){
					$user = $results[0];
					$this->setupSession($user);
					return $this->forceLogin($user);
				}
			} 
			return false;
		}

		/** Log user out of system */
		public function logout() {
			$f3=Base::instance();
			$db = $this->controller->db;

			$db->safeQuery(
				'DELETE FROM BlogPress_Cookie WHERE BlogPress_Cookie=?',
				$_COOKIE['BlogPress_User']
			);

			//Kill the session
			session_destroy();

			//Kill the cookie
			setcookie('BlogPress_User','',time()-3600,'/');
		}

		/** Set up the session for the current user */
		public function setupSession($user) {

			// Database connection
			$db = $this->controller->db;

			//Remove previous session
			session_destroy();

			//Generate blogpress_cookie and save
			$blogpress_cookie = base64_encode(openssl_random_pseudo_bytes(64));
			$db->safeQuery(
				'INSERT INTO BlogPress_Cookie(user_id,BlogPress_Cookie,maxlifetime) values(?,?,?)',
				array($user['id'],$blogpress_cookie,time()+3600*24*30)
			);

			//Generate unique session id 
			$sessid = base64_encode(openssl_random_pseudo_bytes(64));

			//Setup new session
			session_id(md5($sessid));

			//Setup cookie for storing user details and for relogging in
			setcookie('BlogPress_User',$blogpress_cookie,time()+3600*24*30,'/');

			//And begin!
			new Session();
		}

		/** Not used anywhere in the code, for debugging only */
		public function specialLogin($username) {
			//YOU ARE NOT ALLOWED TO CHANGE THIS FUNCTION
			$f3 = Base::instance();
			$user = $this->controller->Model->Users->fetch(array('username' => $username));
			$array = $user->cast();
			return $this->forceLogin($array);
		}

		/** Not used anywhere in the code, for debugging only */
		public function debugLogin($username,$password='admin') {
			//YOU ARE NOT ALLOWED TO CHANGE THIS FUNCTION
			$user = $this->controller->Model->Users->fetch(array('username' => $username));

			//Create a new user if the user does not exist
			if(!$user) {
				$user = $this->controller->Model->Users;
				$user->username = $user->displayname = $username;
				$user->email = "$username@blogpress.org";
				$user->setPassword($password);
				$user->created = mydate();
				$user->bio = '';
				$user->level = 2;
				$user->save();
			}

			//Update user password
			$user->setPassword($password);

			//Move user up to administrator
			if($user->level < 2) {
				$user->level = 2;
				$user->save();
			}

			//Log in as new user
			return $this->forceLogin($user);			
		}

		/** Force a user to log in and set up their details */
		public function forceLogin($user) {
			//YOU ARE NOT ALLOWED TO CHANGE THIS FUNCTION
			$f3=Base::instance();					

			if(is_object($user)) { $user = $user->cast(); }
			$f3->set('SESSION.user',$user);
			return $user;
		}

		/** Get information about the current user */
		public function user($element=null) {
			$f3=Base::instance();
			if(!$f3->exists('SESSION.user')) { return false; }
			if(empty($element)) { return $f3->get('SESSION.user'); }
			else { return $f3->get('SESSION.user.'.$element); }
		}

	}

?>
