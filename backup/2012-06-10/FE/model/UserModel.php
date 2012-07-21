<?php
class UserModel extends ControllerModel
{
	protected $tables = array(
		'UserMessages',
	);

	/************************************ FORM VALIDATOR FUNCTIONS ************************************/
	public function usernameExists($value)
	{
		return !Users::usernameExists($value);
	}

	public function emailExists($value)
	{
		return !Users::emailExists($value);
	}
	public function checkLogin($value)
	{
		$username	= $value;
		$password	= $_POST['form_login']['password'];

		return Users::checkLogin($username, $password);
	}

}
?>