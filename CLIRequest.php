<?php

namespace Ivory;

class CLIRequest {

	/**
	 * The $_REQUEST variable as passed from the Command Line
	 * @var array
	 */
	protected $request;

		/**
	 * The argv passed by the PHP script
	 * @var array
	 */
	protected $argv;

	/**
	 * The arguments passed to the application
	 * @var array
	 */
	protected $arguments = [];

	/**
	 * The options provided to the application. Options 
	 * begin with double hyphens.
	 * @var array
	 */
	protected $options = [];

	/**
	 * The flags provided to the applicaiton. Flags
	 * being with a single hyphen.
	 * @var array
	 */
	protected $flags = [];

	/**
	 * The command that was typed. 
	 * @var string
	 */
	protected $command;

	/**
	 * Class Consctuctor
	 * @param array
	 */
	public function __construct()
	{
    	global $_SERVER;

		$this->request = $_SERVER;
		$this->_extractArgs();
	}

	/**
	 * Extract the arguments into the appropriate arrays
	 * @return void
	 */
	protected function _extractArgs()
	{
		$argv = $this->request['argv'];
		unset($argv[0]); // This is the script name, we don't need it

		//The arguments passed to the application
		foreach( $argv as $index => $arg ) :
			/**
			 * First check for an option. arguments start with two hyphens. So if the string
			 * starts with two hyphens we can extract it accordingly
			 */
			if( substr($arg, 0, 2) == '--' ) :
				$this->_extractOption($arg);
			elseif( substr($arg, 0, 1) == '-' ) :			
			/**
			 * Next check for flags. Flags start with only a single hyphen
			 */
				$this->_extractFlag($arg);
			else:
			/**
			 * If there are no hyphens at all, then a command was passed. 
			 */
				$this->_extractCommand($arg);
			endif;
		endforeach;		
	}

	/**
	 * Extracts an option and adds it to the options array
	 * @param  string $option The option to extract
	 */
	protected function _extractOption(string $option)
	{
		// First we'll remove the two hyphens as they aren't needed
		$option = substr($option, 2);

		// Next we'll extract the name of the option and seperate it from it's value
		$equalSign = strpos($option, "=");
		
		$optionName = camel_case(substr($option, 0, $equalSign));
		$optionValue = substr($option, ($equalSign) + 1 );
		$this->options[$optionName] = $optionValue;
	}

	protected function _extractFlag(string $flag)
	{
		$this->flags[] = camel_case(substr($flag, 1));
	}

	protected function _extractCommand($command)
	{		
		$this->command = trim($command);
	}

	public function __get($property)
	{
		if( property_exists($this, $property)) {
			return $this->$property;
		}
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

	/**
	 * Check to see if a specific flag exists
	 * @param  string $flagName The name of the flag to check for
	 * @return bool           True/False
	 */
	public function flag($flagName)
	{
		return !! array_key_exists($flagName, $this->flags);
	}

	/**
	 * Return an array of all the flags that are set
	 * @return array 
	 */
	public function flags()
	{
		return $this->flags;
	}

	/**
	 * Return whether or not a specific option exists
	 * @param  string  $option 
	 * @return boolean         
	 */
	public function hasOption(string $option)
	{
		return !! array_key_exists($option, $this->options);
	}

	/**
	 * Return a requested option's value
	 * @param  string $option 
	 * @return string         
	 */
	public function option(string $option)
	{
		if( $this->hasOption($option) ) return $this->options[$option];
	}

	/**
	 * Return all the commands in progress for this cycle
	 * @return array 
	 */
	public function commandCalled()
	{
		return $this->command;
	}

	/**
	 * Return the primary command for this run
	 * @return string 
	 */
	public function primaryCommand()
	{
		if( !empty($this->commands ) ) return $this->commands[0];
	}

}
