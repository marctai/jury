<?php

class Session
{
	static public function loggedIn()
	{
		return isset($_SESSION['user']);
	}

	static public function setUser($user)
	{
		if (isset($_SESSION['user']))
		{
			unset($_SESSION['user']);
		}

		$_SESSION['user'] = $user;
	}
}