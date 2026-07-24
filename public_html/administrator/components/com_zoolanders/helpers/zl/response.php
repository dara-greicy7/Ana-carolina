<?php
/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class zlHelperResponse
 */
class zlHelperResponse extends AppHelper
{
	/**
	 * Create Json response, binded with provided data
	 *
	 * @param   mixed       Data
	 * @param   int         HTTP response code
	 *
	 * @return  ZlResponse
	 */
	public function create($code = 200, $data = array()){
		return new ZlJSONResponse($code, $data);
	}
}

/**
 * Class JsonResponse
 * HTTP Response helper
 */
class ZlJSONResponse extends JHttpResponse
{
	/**
	 * @var string Data
	 */
	public $data = array();

	/**
	 * @var array   Used HTTP states codes
	 */
	private static $status_codes = array(
		200 => 'OK',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		500 => 'Internal Server Error'
	);

	/**
	 * Response constructor
	 *
	 * @param   int $code
	 * @param   $data
	 */
	public function __construct($code = 200, $data = array()){
		$this->code = $code;
		$this->data = $data;
	}

	/**
	 * Bind variable to data
	 *
	 * @param   string  Varname
	 * @param   mixed   Value
	 *
	 * @return  object
	 */
	public function __set($varname, $value)
	{
		if (null === $this->data){
			$this->data = array();
		}

		if (is_array($this->data)){
			$this->data[$varname] = $value;
		} elseif(is_object($this->data)) {
			$this->data->{$varname} = $value;
		} else {
			$this->data = $value;
		}

		return $this;
	}

	/**
	 * Get variable from data
	 *
	 * @param   string  Varname
	 *
	 * @return  mixed
	 */
	public function __get($varname)
	{
		$value = null;

		if (is_array($this->data) && array_key_exists($varname, $this->data)){
			$value = $this->data[$varname];
		} elseif(is_object($this->data) && property_exists(get_class($this->data), $varname)) {
			$value = $this->data->{$varname};
		}

		return $value;
	}

	/**
	 * Set response header
	 *
	 * @param   $key
	 * @param   $value
	 *
	 * @return  void
	 */
	public function setHeader($key, $value)
	{
		$this->headers[$key] = $value;
		return $this;
	}

	/**
	 * Send HTTP headers
	 *
	 * @return void
	 */
	protected function sendHeaders()
	{
		header($_SERVER["SERVER_PROTOCOL"]." $this->code " .@self::$status_codes[$this->code]);
		$this->setHeader('Content-Type', 'application/json');

		if (!empty($this->headers)){
			foreach ($this->headers as $key => $value) {
				header(sprintf("%s: %s", $key, $value));
			}
		}
	}

	/**
	 * Set a root value
	 *
	 * @param $varname
	 * @param $value
	 *
	 * @return object
	 */
	public function set($varname, $value)
	{
		$this->{$varname} = $value;
		return $this;
	}

	/**
	 * Add a value to subarray (for example for errors)
	 *
	 * @param $varname
	 * @param $value
	 *
	 * @return object
	 */
	public function add($varname, $value)
	{
		$node = $this->{$varname};
		if (empty($node)){
			$this->{$varname} = array();
			$node = array();
		}

		$this->{$varname} = $value;
		return $this;
	}

	/**
	 * Send prepared response to user agent
	 *
	 * @return  mixed
	 */
	public function send()
	{
		$this->sendHeaders();

		if (!empty($this->data)) {
			echo json_encode($this->data);
		} else if (@self::$status_codes[$this->code]) {
			echo @self::$status_codes[$this->code];
		}

		exit();
	}
}
