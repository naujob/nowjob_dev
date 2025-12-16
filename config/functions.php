<?php
/*
 * ------------------------------------------------------
 *  Load the framework constants
 * ------------------------------------------------------
 */
if (file_exists(SHAREDPATH . 'config/'.ENVIRONMENT.'/constants.php'))
{
    require_once(SHAREDPATH . 'config/'.ENVIRONMENT.'/constants.php');
}

if (file_exists(SHAREDPATH . 'config/constants.php'))
{
    require_once(SHAREDPATH . 'config/constants.php');
}

if ( ! function_exists('is_cli'))
{

	/**
	 * Is CLI?
	 *
	 * Test to see if a request was made from the command line.
	 *
	 * @return 	bool
	 */
	function is_cli()
	{
		return (PHP_SAPI === 'cli' OR defined('STDIN'));
	}
}

if ( ! function_exists('set_status_header'))
{
	/**
	 * Set HTTP Status Header
	 *
	 * @param	int	the status code
	 * @param	string
	 * @return	void
	 */
	function set_status_header($code = 200, $text = '')
	{
		if (is_cli())
		{
			return;
		}

		if (empty($code) OR ! is_numeric($code))
		{
			show_error('Status codes must be numeric', 500);
		}

		if (empty($text))
		{
			is_int($code) OR $code = (int) $code;
			$stati = array(
				100	=> 'Continue',
				101	=> 'Switching Protocols',

				200	=> 'OK',
				201	=> 'Created',
				202	=> 'Accepted',
				203	=> 'Non-Authoritative Information',
				204	=> 'No Content',
				205	=> 'Reset Content',
				206	=> 'Partial Content',

				300	=> 'Multiple Choices',
				301	=> 'Moved Permanently',
				302	=> 'Found',
				303	=> 'See Other',
				304	=> 'Not Modified',
				305	=> 'Use Proxy',
				307	=> 'Temporary Redirect',

				400	=> 'Bad Request',
				401	=> 'Unauthorized',
				402	=> 'Payment Required',
				403	=> 'Forbidden',
				404	=> 'Not Found',
				405	=> 'Method Not Allowed',
				406	=> 'Not Acceptable',
				407	=> 'Proxy Authentication Required',
				408	=> 'Request Timeout',
				409	=> 'Conflict',
				410	=> 'Gone',
				411	=> 'Length Required',
				412	=> 'Precondition Failed',
				413	=> 'Request Entity Too Large',
				414	=> 'Request-URI Too Long',
				415	=> 'Unsupported Media Type',
				416	=> 'Requested Range Not Satisfiable',
				417	=> 'Expectation Failed',
				422	=> 'Unprocessable Entity',
				426	=> 'Upgrade Required',
				428	=> 'Precondition Required',
				429	=> 'Too Many Requests',
				431	=> 'Request Header Fields Too Large',

				500	=> 'Internal Server Error',
				501	=> 'Not Implemented',
				502	=> 'Bad Gateway',
				503	=> 'Service Unavailable',
				504	=> 'Gateway Timeout',
				505	=> 'HTTP Version Not Supported',
				511	=> 'Network Authentication Required',
			);

			if (isset($stati[$code]))
			{
				$text = $stati[$code];
			}
			else
			{
				show_error('No status text available. Please check your status code number or supply your own message text.', 500);
			}
		}

		if (strpos(PHP_SAPI, 'cgi') === 0)
		{
			header('Status: '.$code.' '.$text, TRUE);
			return;
		}

		$server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.0', 'HTTP/1.1', 'HTTP/2'), TRUE))
			? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
		header($server_protocol.' '.$code.' '.$text, TRUE, $code);
	}
}

function &load_class($class, $directory = 'libraries', $param = NULL)
{
    static $_classes = array();

    // Does the class exist? If so, we're done...
    if (isset($_classes[$class]))
    {
        return $_classes[$class];
    }

    $name = FALSE;
	
	

    // Look for the class first in the local application/libraries folder
    // then in the native system/libraries folder
    foreach (array(SHAREDPATH, APPPATH, BASEPATH) as $path)
    {
        if (file_exists($path.$directory.'/'.$class.'.php'))
        {
       
            $name = 'CI_'.$class;

            if (class_exists($name, FALSE) === FALSE)
            {
                require_once($path.$directory.'/'.$class.'.php');
            }

            break;
        }
    }

    // Is the request a class extension? If so we load it too
    if (file_exists(APPPATH.$directory.'/'.config_item('subclass_prefix').$class.'.php'))
    {
        $name = config_item('subclass_prefix').$class;

        if (class_exists($name, FALSE) === FALSE)
        {
            require_once(APPPATH.$directory.'/'.$name.'.php');
        }
    } else if (file_exists(SHAREDPATH.$directory.'/'.config_item('subclass_prefix').$class.'.php')){
        $name = config_item('subclass_prefix').$class;

        if (class_exists($name, FALSE) === FALSE)
        {
            require_once(SHAREDPATH.$directory.'/'.$name.'.php');
        }
    }
	
	
    // Did we find the class?
    if ($name === FALSE)
    {
        // Note: We use exit() rather than show_error() in order to avoid a
        // self-referencing loop with the Exceptions class
        set_status_header(503);
        echo 'Unable to locate the specified class: '.$class.'.php';
        exit(5); // EXIT_UNK_CLASS
    }

    // Keep track of what we just loaded
    is_loaded($class);

    $_classes[$class] = isset($param)
        ? new $name($param)
        : new $name();
    return $_classes[$class];
}

if ( ! function_exists('get_config'))
{
	/**
	 * Loads the main config.php file
	 *
	 * This function lets us grab the config file even if the Config class
	 * hasn't been instantiated yet
	 *
	 * @param	array
	 * @return	array
	 */
    // get_config 재정의
	function &get_config(Array $replace = array())
	{
		static $config;

		if (empty($config))
		{
			$file_path = APPPATH.'config/config.php';
			$found = FALSE;
			if (file_exists($file_path))
			{
				$found = TRUE;
				require($file_path);
			}

			// Is the config file in the environment folder?
			if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config.php'))
			{
				require($file_path);
            }
            else if (file_exists($file_path = SHAREDPATH . 'config/config.php') === true) {
                require($file_path);
            }
            else if (file_exists($file_path = SHAREDPATH . 'config/' . ENVIRONMENT . '/config.php') === true) {
                require($file_path);
            }
			elseif ( ! $found)
			{
				set_status_header(503);
				echo 'The configuration file does not exist.';
				exit(3); // EXIT_CONFIG
			}

			// Does the $config array exist in the file?
			if ( ! isset($config) OR ! is_array($config))
			{
				set_status_header(503);
				echo 'Your config file does not appear to be formatted correctly.';
				exit(3); // EXIT_CONFIG
			}
		}

		// Are any values being dynamically added or replaced?
		foreach ($replace as $key => $val)
		{
			$config[$key] = $val;
		}

		return $config;
	}
}

if ( ! function_exists('get_mimes'))
{
	/**
	 * Returns the MIME types array from config/mimes.php
	 *
	 * @return	array
	 */
	function &get_mimes()
	{
		static $_mimes;

		if (empty($_mimes))
		{
			$_mimes = file_exists(APPPATH.'config/mimes.php')
				? include(APPPATH.'config/mimes.php')
				: array();

			if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/mimes.php'))
			{
				$_mimes = array_merge($_mimes, include(APPPATH.'config/'.ENVIRONMENT.'/mimes.php'));
            }

            if (file_exists(SHAREDPATH . 'config/mimes.php') === true) {
                $_mimes = array_merge($_mimes, include(SHAREDPATH . 'config/mimes.php'));
            }

            if (file_exists(SHAREDPATH . 'config/' . ENVIRONMENT . '/mimes.php') === true) {
                $_mimes = array_merge($_mimes, include(SHAREDPATH . 'config/' . ENVIRONMENT . '/mimes.php'));
            }
		}

		return $_mimes;
	}
}