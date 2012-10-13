<?php
namespace Sweany;
class ErrorHandler
{
	/**
	 * Custom ob error handler
	 *
	 * When in debugging mode, ob_start will use
	 * this function as a callback to be able
	 * to display errors during output buffering.
	 *
	 * (In production mode it will use a compression func)
	 */
	public static function ob_error_handler($str)
	{
		$error	= error_get_last();

		$trace	= debug_backtrace();
		$trace	= isset($trace[count($trace)-1]) ? $trace[count($trace)-1] : null;

		$class	= isset($tace['class'])		? $trace['class'].'->'	: '';
		$func	= isset($trace['function'])	? '<strong>'.$trace['function'].'</strong>'	: '<strong>NO_FUNCTION</strong>';
		$file	= isset($trace['file'])		? pathinfo($trace['file'],PATHINFO_BASENAME) : 'NO_FILE';
		$line	= isset($trace['line'])		? $trace['line']		: 'NO_LINE';

		$from	= $class.$func.' in '.$file.':'.$line;

		// We need this to be false, otherwise SysLog will print the error,
		// which will also lead to an error and nothing would be displayed
		$GLOBALS['BREAK_ON_ERROR'] = 0;

		\Sweany\SysLog::i('core', 'Output Buffering', 'Using ob_error_handler() from '.$from);

		// If error orrocured
		if ($error)
		{
			$error = ini_get('error_prepend_string').
						"\n".'Fatal error: '.$error['message'].' in '.$error['file'].' on line '.$error['line']."\n".
						ini_get('error_append_string');

			\Sweany\SysLog::e('core', 'Output Buffering', $error);

			$error =\Sweany\SysLog::show(true);

			return $error;
		}
		return $str;
	}

	/**
	 * Custom PHP Error handler
	 *
	 * Allows a nicer screen output for errors.
	 * This function is called internally by php.
	 */
	public static function php_error_handler($errno, $error, $file, $line, $context)
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

//				break;

			case E_USER_WARNING:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px #FFA500;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:#FFA500; font-weight:bold;">Warning ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
//				break;

			case E_USER_NOTICE:
				echo '<div style="z-index:555;font-size:11px; font-family:arial;border:solid 1px #FFA500;padding:5px;background:gray">';
				echo	'<span style="color:#FFA500; font-weight:bold;">Notice ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
//				break;

			case E_WARNING:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px red;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:red; font-weight:bold;">Runtime Error ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
//				break;

			case E_NOTICE:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px #FFA500;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:#FFA500; font-weight:bold;">Runtime Notice ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
//				break;

			default:
				echo '<div style="z-index:555;font-size:11px;font-family:arial;border:solid 1px red;padding:5px;background:gray;color:black;">';
				echo	'<span style="color:red; font-weight:bold;">Unknown Error ['.$errno.']</span>: <strong>'.$error.'</strong><br/><br/>';
				echo	'<strong>File:</strong> '.$file.'<br/>';
				echo	'<strong>Line:</strong> '.$line.'<br/>';
				echo	'<strong>PHP:</strong> ' . PHP_VERSION . ' (' . PHP_OS . ')<br/><br/>';
//				debug($context);
				echo '</div>';
//				break;
		}
		// IMPORTANT!!! Flush all buffers on error,
		// otherwise it will not be displayed
		self::flushError();

		/* true, so PHP does not execute internal error handling */
		return true;
	}

	// EXCEPTIONS
	public static function php_exception_handler($exception)
	{
		// Display content $exception variable
		echo '<pre>';
		print_r( $exception );
		echo '</pre>';

		// IMPORTANT!!! Flush all buffers on error,
		// otherwise it will not be displayed
		self::flushError();
	}

	// UNCATCHABLE ERRORS
	public static function php_shutdown_handler()
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

			// IMPORTANT!!! Flush all buffers on error,
			// otherwise it will not be displayed
			self::flushError();
		}
		else
		{
			return true;
		}
	}



	private static function flushError()
	{
		if (ob_get_length())
		{
			ob_flush();
			flush();
			ob_end_flush();
		}
		ob_start();
	}
}