<?php
namespace app\controllers;

class User extends \app\core\Controller{
	
	function login(){
		//show the login form and log the user in
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			//log the user in... if the password is right
			//get the user from the database
			$username = $_POST['username'];
			$user = new \app\models\User();
			$user = $user->get($username);
			//check the password against the hash
			$password = $_POST['password'];
			if($user && $user->active && password_verify($password, $user->password_hash)){
				//remember that this is the user logging in...
				//this unset probably shouldnt be here but it makes my code word now even tho it completely undermines my secureplace
				unset($_SESSION['user_id']);
				$_SESSION['user_id'] = $user->user_id;

				header('location:/User/securePlace');
			}else{
				header('location:/User/login');
			}
		}else{
			$this->view('User/login');
		}
	}

	function logout(){
		//session_destroy();
		//$_SESSION['user_id'] = null;

		session_destroy();

		header('location:/Person/home');
	}

	function home(){
		header('location:/User/securePlace');
	}

	function securePlace(){
		if(!isset($_SESSION['user_id'])){
			header('location:/User/login');
			return;
		}
		$this->view('User/home');
	}

	function register(){
		//display the form and process the registration
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			//getting the user input and place it in an object
			//create the new User
			$user = new \app\models\User();
			//populate the User
			$user->username = $_POST['username'];
			$user->password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
			if ($user->username != null && $user->password_hash!= null){
			//insert the user
			$user->insert();
			//redirect to a good place
			header('location:/User/login');
		}
		}else{			 
			$this->view('User/registration');
		}
	}

	//update our own user record (only if I am logged in)
	function update(){
		if(!isset($_SESSION['user_id'])){
			header('location:/User/login');
			return;
		}

		$user = new \app\models\User();
		$user = $user->getById($_SESSION['user_id']);

		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			//process the update
			$user->username = $_POST['username'];
			//change the password only if one is sent
			$password = $_POST['password'];
			if(!empty($password)){//should be false if ''
				$user->password_hash = password_hash($password, PASSWORD_DEFAULT);
			}//otherwise remains as it was
			$user->update();
			header('location:/User/update');
		}else{
			$this->view('User/update', $user);
		}
	}

	function delete(){
		if(!isset($_SESSION['user_id'])){//is not logged in
			header('location:/User/login');
			return;
		}

		$user = new \app\models\User();
		$user = $user->getById($_SESSION['user_id']);
		$user->delete();
		header('location:/User/logout');
	}

}