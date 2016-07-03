<?php
	require_once('shared.php');	

	$login_err;
	$signup_err;

	// DatabaseHelper::getCustomer('ziitai','SYM32XKE0VT');	

	if(!empty($_USER_)) {
		Utils::redirect(__APPURL__ . '/index.php');
	}
	else if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$action = $_POST['action'];

		$username = $_POST['username'];
		$password = $_POST['password'];

		if($action == 'login') {
			if(empty($username) || empty($password) ||
				!AuthenticationHelper::login($username,$password)) { 
				$login_err = 'username and/or password is invalid';
			}
			else {
				Utils::redirect(__APPURL__ . '/index.php');
			}
		}
		else if ($action == 'signup') {

			$firstName = $_POST['first_name'];
			$lastName = $_POST['last_name'];
			$address = $_POST['address'];
			$phoneNumber = $_POST['phone_number'];
			$creditCardNum = $_POST['credit_card_number'];

			if(empty($username) || empty($password) || 
				empty($firstName) || empty($lastName) ||
				empty($address) || empty($phoneNumber) || empty($creditCardNum)) { 				
				$signup_err = 'all fields are required';
			}
			else if (!AuthenticationHelper::signup($username,
					$password,
					$firstName,
					$lastName,
					$address,
					$phoneNumber,
					$creditCardNum))
			{
				$signup_err = 'sorry that username is already taken';
			}
			else if (!AuthenticationHelper::login($username,$password)) {
				$signup_err = 'no idea what went wrong';
			}
			else {				
				Utils::redirect(__APPURL__ . '/index.php');
			}
		}		
	}
?>

<?php include('layouts/header.php'); ?>

<div class="container-960">
	<div class="row">
		<div class="col-md-5">
			<div class="form-container">
				<h2 class="stylish title">Login</h2>
				<p class="text-danger"><?=(isset($login_err) ? $login_err : '')?></p>
				<form action="login.php" method="POST">				
					<div class="input-group">
					  <span class="input-group-addon">@</span>
					  <input type="text" name="username" class="form-control" placeholder="Username" required>
					</div>
					<div class="input-group">
					  <span class="input-group-addon">* </span>
					  <input type="password" name="password" class="form-control" placeholder="Password" required>
					</div>
					<button name="action" value="login" class="btn btn-success" type="submit">Login</button>
				</form>							
			</div>			
		</div>

		<div class="col-md-2">
			<h2 class="stylish centered-title">Or</h2>
		</div>

		<div class="col-md-5">
			<div class="form-container">
				<h2 class="stylish title">SignUp</h2>
				<p class="text-danger"><?=(isset($signup_err) ? $signup_err : '')?></p>
				<form action="login.php" method="POST">				
					<div class="input-group">
					  <span class="input-group-addon">@</span>
					  <input type="text" name="username" class="form-control" placeholder="Username" required>
					</div>
					<div class="input-group">
					  <span class="input-group-addon">* </span>
					  <input type="password" name="password" class="form-control" placeholder="Password" required>
					</div>
					<div class="form-group">
					  <label>First Name</label>
					  <input type="text" name="first_name" class="form-control" placeholder="Sherlock" required>
					</div>
					<div class="form-group">
					  <label>Last Name</label>
					  <input type="text" name="last_name" class="form-control" placeholder="Holmes" required>
					</div>
					<div class="form-group">
					  <label>Address</label>
					  <input type="text" name="address" class="form-control" placeholder="221, Baker Street, London" required>
					</div>
					<div class="form-group">
					  <label>Phone Number</label>
					  <input type="text" name="phone_number" class="form-control" placeholder="+92 331 4977454" required>
					</div>
					<div class="form-group">
					  <label>Credit Card Number</label>
					  <input type="text" name="credit_card_number" class="form-control" placeholder="xxxx xxxx xxxx xxxx" required>
					</div>
					<button name="action" value="signup" class="btn btn-primary" type="submit">Sign Up</button>
				</form>							
			</div>			
		</div>
		<div 
	</div>
</div>	

<?php include('layouts/footer.php'); ?>
