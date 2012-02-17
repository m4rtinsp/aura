<?php
	/**
	 * Aura PHP Framework - Basic class
	 * 
	 * @author	Paulo Martins <phmartins6@gmail.com>
	 */
	
	abstract class basics
	{
		static private $instanceModels;
		static private $instanceVendors;
		static private $instanceHelper;
		
		/**
		 * Creates objects
		 * @return Object
		 */
		public function __get($name)
		{
			$property = ucwords(strtolower($name));
			return self::set_instance($name);
		}
		
		/**
		 * Checks if the class exists, and can be instantiating
		 * @param object $name
		 * @return Object
		 */
		protected function set_instance($name)
		{
			if (!is_array(self::$instanceModels))
				self::$instanceModels = array();
			
			$model_name = $name . 'Model';
			$model_path = AURA_APP . DS . Aura::$app . DS . 'models' . DS . $model_name . '.php';
			
			if (!file_exists($model_path))
				return false;

			if (!array_key_exists(strtolower($model_name), self::$instanceModels)) {
				$this->required_file($model_path);
				
				$model = new $model_name;
				self::$instanceModels[strtolower($model_name)] = $model;	
			}
			
			return self::$instanceModels[strtolower($model_name)];
		}
		
		
		/**
		 * Import vendors
		 * @param object $name : File name without extension
		 * @param array $array_params [optional] : Class properties (pass in array
		 * @param bool $instance [optional] : Pass false if you want to just include the file
		 * @return Object/Bool
		 */
		public function vendors($name, $array_params = null, $instance = true)
		{
			// remove first bar
			$name = substr($name, 0, 1) == '/' ? substr($name, 1, strlen($name)) : $name;
			$name = str_replace('/', DS, $name);
			$vendor_file = "$name.php";
			$name = explode(DS, $name);
			$file = AURA_ROOT . DS . 'vendors' . DS . $vendor_file;
			// get class name
			$name = $name[count($name)-1];
			
			if (!$instance) {
				include_once($file);
				return true;
			}
			
			if (!is_array(self::$instanceVendors))
				self::$instanceVendors = array();
			
			if (is_file($file)) {
				if (!array_key_exists($name, self::$instanceVendors)) {
					include_once($file);
					
					if (!class_exists($name))
						return false;
					
					if(!$array_params)
						$object = new $name;
					else {
						$pr = null;
						
						foreach($array_params as $param) {
							if(is_numeric($param))
								$pr .= $param . ',';
							else
								$pr .= '\'' . $param . '\',';
						}
						
						$pr = substr($pr, 0, -1);
						eval('$object = new '.$name.'('.$pr.');');
					}
					
					self::$instanceVendors[strtolower($name)] = $object;
				}
				
				return self::$instanceVendors[strtolower($name)];
			}
			else
				return false;
		}
		
		/**
		 * session manager
		 * @param object $name : Session name
		 * @param object $value [optional] : Session value (pass 'destroy' to kill the sessions)
		 * @return 
		 */
		function session($name, $value = false)
		{
			if (!isset($_SESSION))
				@session_start();
			
			if ($name == 'destroy') {
				session_unset();
				session_destroy();
				return;
			}
			
			if (!isset($_SESSION[$name]) && $value !== false)
				$_SESSION[$name] = $value;
			else
				$value !== false ? $_SESSION[$name] = $value : false;
			
			return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
		}
		
		/**
		 * Redirect url
		 * @param string $url : /controller name
		 * @param string $app [optional] : App name. Null for external url
		 * @param bool $external [optional] : True for external url 
		 * @return 
		 */
		function redirect($url, $app = null, $external = false)
		{
			if ($external)
				header('Location: ' . $url);
			elseif ($app)
				header('Location: ' . aura_root . '/' . $app . '/' . $url);
			else
				header('Location: ' . app_root . $url);
		}
		
		/**
		 * Message management (this method uses cookie)
		 * @param string $key
		 * @param string $message [optional]
		 * @param int $time [optional] : In milliseconds
		 * @return bool/string/array
		 */
		function message($key = null, $message = null, $time = null)
		{
			$key = strtoupper($key);
			
			if ($key && !is_null($message)) {
				$time = $time ? $time : time()+60*60*24;

				if (isset($_COOKIE[AURA_PREFIX_COOKIE . 'AURA_FRAMEWORK_MESSAGE_' . $key . AURA_POSFIX_COOKIE]))
					setcookie(AURA_PREFIX_COOKIE . 'AURA_FRAMEWORK_MESSAGE_' . $key . AURA_POSFIX_COOKIE, "", -$time, '/', '.' . $_SERVER['SERVER_NAME']);

				if ($message !== false)
					return setcookie(AURA_PREFIX_COOKIE . 'AURA_FRAMEWORK_MESSAGE_' . $key . AURA_POSFIX_COOKIE, $message, $time, '/', '.' . $_SERVER['SERVER_NAME']);
				else
					return true;
			}
			elseif ($key) {
				return isset($_COOKIE[AURA_PREFIX_COOKIE . 'AURA_FRAMEWORK_MESSAGE_' . $key . AURA_POSFIX_COOKIE]) ? $_COOKIE[AURA_PREFIX_COOKIE . 'AURA_FRAMEWORK_MESSAGE_' . $key . AURA_POSFIX_COOKIE] : false;
			}
			else {
				$framework_cookies = false;

				if (isset($_COOKIE)) {
					foreach ($_COOKIE as $name => $content) {
						if (strpos($name, 'AURA_FRAMEWORK_MESSAGE_') !== false) {
							$name = explode('AURA_FRAMEWORK_MESSAGE_', $name);
							$framework_cookies[$name[1]] = $content;
						}
					}
				}

				return $framework_cookies;
			}
		}
		
		protected function required_file($path)
		{
			require $path;
		}
	}
?>