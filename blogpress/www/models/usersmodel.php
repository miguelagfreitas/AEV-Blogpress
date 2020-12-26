<?php

class UsersModel extends GenericModel {

	/** Update the password for a user account */
	public function setPassword($password) {
		// Avoiding having the password in cleartxt
		$salt = openssl_random_pseudo_bytes(16);

		// Hashing the password
		$hashed_password = hash_pbkdf2("sha256",$password,$salt,50,20);
		$this->password = $hashed_password;
		$this->salt = base64_encode($salt);
	}		
}

?>
