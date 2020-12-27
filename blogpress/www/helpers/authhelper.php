<?php

	class AuthHelper {
		
		/** Construct a new Auth helper */
		public function __construct($controller) {
			$this->controller = $controller;
		}

		/** Attempt to resume a previously logged in session if one exists */
		public function resume() {
			$f3=Base::instance();				

			//Ignore if already running session	
			if($f3->exists('SESSION.user.id')) return;

			//Log user back in from cookie
			if($f3->exists('COOKIE.BlogPress_User')) {	
				$user = unserialize(base64_decode($f3->get('COOKIE.BlogPress_User')));
				$this->forceLogin($user);
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

		/** Look up user by username and password and log them in */
		/*
		public function login($username,$password) {
			$f3=Base::instance();						
			$db = $this->controller->db;
			$results = $db->safeQuery('SELECT * FROM users WHERE username=? AND password=?', array($username, $password));

			if (!empty($results)) {
				$user = $results[0];
				$this->setupSession($user);
				return $this->forceLogin($user);
			}
			return false;
		}*/


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
			\StatusMessage::add($saved_pass,'danger');
			return false;
		}

		/** Log user out of system */
		public function logout() {
			$f3=Base::instance();							

			//Kill the session
			session_destroy();

			//Kill the cookie
			setcookie('BlogPress_User','',time()-3600,'/');
		}

		/** Set up the session for the current user */
		public function setupSession($user) {

			//Remove previous session
			session_destroy();

			//Setup new session
			session_id(md5($user['id']));

			//Setup cookie for storing user details and for relogging in
			setcookie('BlogPress_User',base64_encode(serialize($user)),time()+3600*24*30,'/');

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
