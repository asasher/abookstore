<?php
class AuthenticationHelper {
	private static $managerLogin = 'manager';
	private static $managerPass = 'manager';
	private static $cookieName = 'authenticate';

    public static function authenticate() {
    	$cookie = $_COOKIE[static::$cookieName];
    	if(!empty($cookie)) {    		
    		$cookieParts = explode('|', $cookie);    		    		
    		if($cookieParts[0] == static::$managerLogin && $cookieParts[1] == sha1(static::$managerPass)) {
	    		$customer = ['Id' => -1,
	    					 'Login' => static::$managerLogin,
	    					 'Password' => static::$managerPass,
	    					 'Manager' => true,];
	    		return $customer;
	    	}
	    	else
	    	{
	    		$customer = DatabaseHelper::getCustomer($cookieParts[0], null);
	    		if($cookieParts[1] == sha1($customer['Password'])) {
	    			return $customer;    			
	    		}
	    	}    		
    	}    	
    	return null;
    }

    public static function logout() {
    	$ok = false;
    	if(!empty($_COOKIE[static::$cookieName])) {
    		unset($_COOKIE[static::$cookieName]);
    		setcookie(static::$cookieName, null, -1, "/");
    		$ok = true;
    	}
    	return $ok;
    }

    public static function login($username, $password) {    
    	$ok = false;
    	if($username == static::$managerLogin && $password == static::$managerPass) {
    		setcookie(static::$cookieName, static::$managerLogin . '|' . sha1(static::$managerPass), time() + (86400 * 30), "/");
    		$ok = true;
    	}
    	else
    	{
    		$customer = DatabaseHelper::getCustomer($username,$password);
	    	if(!empty($customer)) {    		
	    		setcookie(static::$cookieName, $customer['Login'] . '|' . sha1($customer['Password']), time() + (86400 * 30), "/");
	    		$ok = true;
	    	}
    	}
    	
    	return $ok;
    }

    public static function signup($username, $password, $firstName, $lastName, $address, $phoneNumber, $creditCardNumber) {    	
    	return DatabaseHelper::addCustomer($username, $password, $firstName, $lastName, $address, $phoneNumber, $creditCardNumber);;
    }
}