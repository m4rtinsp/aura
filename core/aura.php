<?php
	/**
	 * Core of Aura PHP Framework
	 * 
	 * @author	Paulo Martins <phmartins6@gmail.com>
	 */
	class Aura extends aura_basics
	{
		const controller_posfix = 'Controller';
		
		static public $app;
		static public $controller;
		static $action;
		protected $location;
		protected $app_default;
		protected $controller_default;
		protected $object;
		protected $action_default;
		protected $params;
		
		/**
		 * Defines location var. Core functions
		 * @return 
		 */
		function __construct()
		{
			$this->location = isset($_GET['location']) ? $_GET['location'] : null;
			
			$this->is_file();
			$this->is_console();
			$this->define_app();
			$this->configuration();
			$this->define_controller();
			$this->define_object();
			$this->define_action();
			$this->define_params();
			
			if (AURA_FRAMEWORK_TEST) {
				$this->test();
			}
			
			$this->load();
		}
		
		/**
		 * Verify if is file
		 * @return 
		 */
		private function is_file()
		{
			if ($this->location){
				$ext = explode('.', $this->location);
				$ext = isset($ext[1]) ? $ext[1] : null;
				$path = $this->location;
				
				if ($ext && !is_file($path)) {
					if (strpos('favicon.ico', $path) !== false) {
						$this->log("File: $path Not Found");
						header("HTTP/1.0 404 Not Found");
						exit;
					}
				}
			}
		}
		
		/**
		 * Verify if is console
		 * @return 
		 */
		private function is_console()
		{
			if ($this->location && ($this->location == 'console' OR $this->location == 'console/') && AURA_CONSOLE){
				if ($this->location == 'console/') {
					$PROTOCOL = strtolower(preg_replace('/[^a-zA-Z]/','', $_SERVER['SERVER_PROTOCOL']));
					header('Location: ' . $PROTOCOL . '://' . $_SERVER['SERVER_NAME'] . substr($_SERVER['REQUEST_URI'], 0, -1));
				}

				include dirname(__file__) . DS . 'console' . DS . 'command.php';
				exit;
			}
		}
		
		/**
		 * Defines app
		 * @return 
		 */
		private function define_app()
		{
			self::$app = AURA_DEFAULT_APP;
			$this->app_default = true;
			
			if ($this->location) {
				$app = explode('/', $this->location);
				$app = $app[0];
				
				if (is_dir(AURA_APP . DS . $app)) {
					self::$app = $app;
					$this->app_default = false;
				}
				elseif (is_dir(AURA_APP . DS . AURA_DEFAULT_APP)) {
					self::$app = AURA_DEFAULT_APP;
				}
				else {
					echo "Default application not found.";
					exit;
				}
			}
			else {
				if (!is_dir(AURA_APP . DS . self::$app)) {
					echo "Default application not found.";
					exit;
				}
			}
		}
		
		/**
		 * Load configuration
		 * @return 
		 */
		private function configuration()
		{
			$PATH = explode('index.php', $_SERVER["SCRIPT_NAME"]);
			$HOST = $_SERVER['HTTP_HOST'] . $PATH[0];
			$PATH = substr($PATH[0], 0, -1);
			
			// Constants
			define('aura_root', $PATH);
			define('aura_http_root', "http://{$HOST}");
			define('aura_core_web_root', "http://{$HOST}core/system/assets/web");
			define('app_root', "{$PATH}/" . self::$app);
			define('app_web_root', "http://{$HOST}app/" . self::$app . "/web");
			
			// Requires
			require AURA_APP . DS . self::$app . DS . 'config' . DS . 'settings.php';
			require AURA_APP . DS . self::$app . DS . 'config' . DS . 'routes.php';
			require AURA_APP . DS . self::$app . DS . 'config' . DS . 'database.php';
			require AURA_APP . DS . self::$app . DS . 'controllers' . DS . 'AppController.php';
			require AURA_APP . DS . self::$app . DS . 'models' . DS . 'AppModel.php';
		}
		
		/**
		 * Defines controller
		 * @return 
		 */
		private function define_controller()
		{
			self::$controller = AURA_DEFAULT_CONTROLLER;
			$this->controller_default = true;
			
			if ($this->location) {
				$controller = explode('/', $this->location);
				$i = $this->app_default ? 0 : 1;
					
				if (count($controller) > $i && strlen($controller[$i]) > 0) {
					if (is_file(AURA_APP . DS . self::$app . DS . 'controllers' . DS . ucwords($controller[$i]) . 'Controller.php')) {
        				self::$controller = $controller[$i];
						$this->controller_default = false;
					}
					else {
						$routes = Route::get('controller');
						
						if ($routes) {
							if (array_key_exists($controller[$i], $routes)) {
								self::$controller = $routes[$controller[$i]];
								$this->controller_default = false;
							}
						}
						elseif ($this->app_default && AURA_NATURAL_404) {
							if (AURA_HEADER_404)
								header("HTTP/1.0 404 not found");

							include 'app' . DS . AURA_DEFAULT_APP . DS . 'views' . DS . 'errors' . DS . '404-error.html';
							exit;
						}
						elseif (!$this->app_default && AURA_NATURAL_404) {
							if (AURA_HEADER_404)
								header("HTTP/1.0 404 not found");

							include 'app' . DS . self::$app . DS . 'views' . DS . 'errors' . DS . '404-error.html';
							exit;
						}
					}
				}
			}
			
			if (!is_file(AURA_APP . DS . self::$app . DS . 'controllers' . DS . ucwords(self::$controller) . 'Controller.php')) {
              	$this->display('controller_error', self::$controller);	            	
				exit;
          	}
		}
		
		/**
		 * Defines object of controller
		 * @return 
		 */
		private function define_object()
		{
			$class_name = ucwords(self::$controller) . self::controller_posfix;
			require AURA_APP . DS . self::$app . DS . 'controllers' . DS . $class_name . '.php';
			
			$this->object = new $class_name;
		}
		
		/**
		 * Defines action
		 * @return 
		 */
		private function define_action()
		{
			self::$action = AURA_DEFAULT_ACTION;
			$this->action_default = true;
			
			if ($this->location) {
				$i = $this->app_default ? 0 : 1;
				$i = $this->controller_default ? $i : $i+1;
				$action = explode('/', $this->location);
				$routes = Route::get('action');
				
				if (isset($action[$i]) && strlen($action[$i]) > 0) {
					if ($routes && array_key_exists($action[$i], $routes)) {
						if (is_callable(array($this->object, $routes[$action[$i]]))) {
							self::$action = $routes[$action[$i]];
							$this->action_default = false;
						}
					}
					else {
						if (is_callable(array($this->object, $action[$i]))) {
							self::$action = $action[$i];
							$this->action_default = false;
						}
					}
				}
			}
			
			if (!is_callable(array($this->object, self::$action))) {
				$this->display('action_error', self::$action);
				exit;
			}
		}
		
		/**
		 * Defines parameters of action
		 * @return 
		 */
		private function define_params()
		{
			if ($this->location) {
				$i = $this->app_default ? 0 : 1;
				$i = $this->controller_default ? $i : $i+1;
				$i = $this->action_default ? $i : $i+1;
				$params = explode('/', $this->location);
				
				if (count($params) > $i) {
					$old_get_name = '';
					$count = 1;
					
					for ($j=0; $j<=($i-1); $j++)
						unset($params[$j]);

					while (list($key, $value) = each($params)) {
						if (!$value)
							unset($params[$key]);
					}
				
					foreach ($params as $param) {
						if ($count % 2 != 0)
							$old_get_name = $param;
						else {
							if(!is_numeric($old_get_name))
								$_GET[$old_get_name] = $param;
						}
						$count++;
					}
					
					$this->params = $params;
				}
			}
		}
		
		/**
		 * Call controller
		 * @return 
		 */
		private function load()
		{
			$params = $this->params ? ' | Params: ' . implode(',', $this->params) : '';
			$this->log('Application: ' . self::$app . ' | Controller: ' . self::$controller . ' | Action: ' . self::$action . $params);
			
			if ($this->params)
				call_user_func_array(array($this->object, self::$action), $this->params);
			else
				call_user_func(array($this->object, self::$action));
		}
	}
?>