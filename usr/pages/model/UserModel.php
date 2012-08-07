<?php
class UserModel extends PageModel
{
	protected $tables = array();

	/************************************ FORM VALIDATOR FUNCTIONS ************************************/
	public function usernameExists($value)
	{
		return !\Core\Init\CoreUsers::usernameExists($value); // TODO: temp solution, have Users helper
	}

	public function emailExists($value)
	{
		return !\Core\Init\CoreUsers::emailExists($value); // TODO: temp solution, have Users helper
	}
	public function checkLogin($value)
	{
		$username	= $value;
		$password	= $_POST['form_login']['password'];

		return \Core\Init\CoreUsers::checkLogin($username, $password); // TODO: temp solution, have Users helper
	}

}
?>
