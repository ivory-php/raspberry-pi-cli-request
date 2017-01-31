<?php

namespace Ivory;

class CLIRequest {

	/**
	 * The $_REQUEST variable as passed from the Command Line
	 * @var array
	 */
	protected $request;

	/**
	 * Class Consctuctor
	 * @param array
	 */
	public function __construct()
	{
    global $_SERVER;

		$this->request = $_SERVER;
	}

	/**
	 * Return the current language the CLI is set to
	 * @return string
	 */
	public function language()
	{
		return $this->request['LANG'];
	}

	/**
	 * Return the base directory the application is running in
	 * @return string
	 */
	public function baseDirectory()
	{
		return $this->request['PWD'];
	}

	/**
	 * Return the command lines $PATH variable
	 * @return string
	 */
	public function path()
	{
		return $this->request['PATH'];
	}

	/**
	 * Return the current user running the script
	 * @return string
	 */
	public function user()
	{
		return $this->request['USER'];
	}

	/**
	 * Return the name that will be used in the system logs
	 * @return string
	 */
	public function logName()
	{
		return $this->request['LOGNAME'];
	}

	/**
	 * Return the location of PHP the server is using
	 * @return string
	 */
	public function phpLocation()
	{
		return $this->request['_'];
	}

	/**
	 * Return the name of the current script running
	 * @return string
	 */
	public function self()
	{
		return $this->request['PHP_SELF'];
	}

	/**
	 * Return the script name variable
	 * @return string
	 */
	public function scriptName()
	{
		return $this->request['SCRIPT_NAME'];
	}

	/**
	 * Return the Filename (including extension) of the script
	 * @return string
	 */
	public function scriptFileName()
	{
		return $this->request['SCRIPT_FILENAME'];
	}

	/**
	 * Return the Time as a float (microtime) the script started
	 * @return float
	 */
	public function requestTimeFloat()
	{
		return $this->request['REQUEST_TIME_FLOAT'];
	}

	/**
	 * Return the unix time stamp of when the script was started
	 * @return int
	 */
	public function requestTime()
	{
		return $this->request['REQUEST_TIME_FLOAT'];
	}

	/**
	 * Return the number of arguments passed to the script.
	 * The script name does not count as an argument, so we'll remove that reference.
	 *
	 * @return int
	 */
	public function numArguments()
	{
		return $this->request['argc'] - 1;
	}

	/**
	 * Retrieve an array of all the argumnts passed to the script. The
	 * script itself being an argument will be removed.
	 *
	 * @return array
	 */
	public function rawArguments()
	{
		$args = $this->request['argv'];
		unset($args[0]);

		if( empty($args) ) return [];

		return array_values($args);
	}

	public function raw()
	{
		return $this->request();
	}

}
