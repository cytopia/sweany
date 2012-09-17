<?php
class CustomError
{
	/**
	 * Custom PHP Error handler
	 *
	 * Allows a nicer screen output for errors.
	 * This function is called internally by php.
	 */
	public static function error_handler($errno, $error, $file, $line, $context)
	{
		switch ($errno)
		{
			case E_USER_ERROR:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px red;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:red; font-weight:bold;">Fatal Error ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo	'EXIT...<br/>';
				echo '</div>';
//				exit(1);
				break;

			case E_USER_WARNING:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px #FFA500;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:#FFA500; font-weight:bold;">Warning ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
				break;

			case E_USER_NOTICE:
				echo '<div style="z-index:555;font-size:11px; font-family:arial;border:solid 1px #FFA500;padding:5px;background:gray">';
				echo	'<span style="color:#FFA500; font-weight:bold;">Notice ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
				break;

			case E_WARNING:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px red;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:red; font-weight:bold;">Runtime Error ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
				break;

			case E_NOTICE:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px #FFA500;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:#FFA500; font-weight:bold;">Runtime Notice ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
				break;

			default:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px red;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:red; font-weight:bold;">Unknown Error ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
				break;
		}

		/* true, so PHP does not execute internal error handling */
		return true;
	}

	// EXTENSIONS
	public static function exception_handler($exception)
	{
		// Display content $exception variable
		echo '<pre>';
		print_r( $exception );
		echo '</pre>';
	}
    
	//UNCATCHABLE ERRORS
	public static function shutdown_handler()
	{
		$error = error_get_last( );
		if( $error )
		{
			## IF YOU WANT TO CLEAR ALL BUFFER, UNCOMMENT NEXT LINE:
			# ob_end_clean( );

			// Display content $error variable
			echo '<pre>';
			print_r( $error );
			echo '</pre>';
		}
		else
		{
			return true;
		}
	}
}