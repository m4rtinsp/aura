<?php
    class aura_controller extends basics
	{
		const smarty_ext = '.tpl';
		
		private $instanceController;
		private $view;
		private $template_folder;
		private $define_smarty;

		/**
		 * Call smarty function and defines directory of templates based in controller name
		 * @return 
		 */
		function __construct()
		{
			if (!isset($this->view))
				$this->define_smarty();
				
			$this->template_folder = strtolower(Aura::$controller) . DS;
		}
		
		/**
		 * Defines smarty object
		 * @return 
		 */
		private function define_smarty()
		{
			$this->view = new Smarty;
			$this->view->template_dir = AURA_APP . DS . Aura::$app . DS . 'views' . DS . 'templates';
			$this->view->compile_dir = AURA_ROOT . DS . 'cache' . DS . Aura::$app . DS . 'template_c';
			
			$this->define_framework_vars();
		}
		
		/**
		 * Defines framework vars
		 * @return 
		 */
		private function define_framework_vars()
		{
			// HTML path
			$html->root = app_web_root;
			$html->css 	= app_web_root . '/css';
			$html->data = app_web_root . '/data';
			$html->files= app_web_root . '/files';
			$html->img 	= app_web_root . '/img';
			$html->js 	= app_web_root . '/js';

			$jsvars  = "<script type='text/javascript' language='JavaScript'>\r\n";
			$jsvars .= "\t\t// Aura PHP Framework: HTML Path\r\n";
			$jsvars .= "\t\taura.path.root = '" . aura_root . "'\r\n";
			$jsvars .= "\t\taura.path.approot = '" . app_root . "'\r\n";
			$jsvars .= "\t\taura.path.http = '" . aura_http_root . "'\r\n\r\n";
			$jsvars .= "\t\t// Web\r\n";
			$jsvars .= "\t\taura.path.root = '" . $html->root . "'\r\n";
			$jsvars .= "\t\taura.path.css = '" . $html->css . "'\r\n";
			$jsvars .= "\t\taura.path.data = '" . $html->data . "'\r\n";
			$jsvars .= "\t\taura.path.img = '" . $html->img . "'\r\n";
			$jsvars .= "\t\taura.path.js = '" . $html->js . "'\r\n";
			$jsvars .= "\t</script>";

			// AURA path
			$aura->js 		= aura_core_web_root . '/aura_php_framework.js';
			$aura->root 	= aura_root;
			$aura->approot 	= app_root;
			$aura->httproot = aura_http_root;
			$aura->jsvars 	= $jsvars;

			if (AURA_CONSOLE)
				echo "Aura Framework: The console is active. Disable to remove this message! Or, <a href='" . aura_root . "/console/'>Show me console</a><br/><br/>";
			if (AURA_FRAMEWORK_TEST)
				echo "Aura Framework: The test is active. Disable to remove this message!<br/><br/>";

			$this->set('aura', $aura);
			$this->set('html', $html);
		}
		
		/**
		* sets variables from ini file (file name is current app)
		* @param string $language
		* @param bool $return : Return array data
		* @return
		**/
		public function locale($language, $return = false)
		{
			$data = Locale::set($language);
			
			if (empty($data))
				return false;
			
			$data = isset($data[Aura::$app]) ? $data[Aura::$app] : false;

			if (!$data)
				return Aura::$app . '.ini not found';
			
			foreach ($data as $key => $var) {
				$this->set($key, $var);
			}

			if ($return)
				return $data;
			else
				return true;
		}

		/**
		 * sets variable from tempalte
		 * @param object $var : Var name
		 * @param object $value : Var value
		 * @return 
		 */
		public function set($var, $value)
		{
			$this->view->assign($var, $value);
		}
		
		/**
		 * Show template
		 * @param object $template : Template name
		 * @param object $master [optional] : Master template name
		 * @return 
		 */
		public function show($template, $master = null)
		{
			if (!$this->view->templateExists($this->template_folder . $template . self::smarty_ext)) {
				$message = 'Template ' . $this->template_folder . $template . self::smarty_ext .' not found';
				Helper::log('framework', $message);
				echo $message;
				exit;
			}
			
			if ($master) {
				if (!$this->view->templateExists(AURA_SMARTY_MASTERPAGES_FOLDER . '/' . $master . self::smarty_ext)) {
					$message = 'Master page ' . $master . self::smarty_ext . ' not found';
					Helper::log('framework', $message);
					echo $message;
					exit;
				}
			}
			else {
				if ($master === false) {
					$this->view->display($this->template_folder . $template . self::smarty_ext);
					exit;
				}

				if (AURA_SMARTY_MASTERPAGE_DEFAULT && AURA_SMARTY_MASTERPAGES_FOLDER) {
					if (!$this->view->templateExists(AURA_SMARTY_MASTERPAGES_FOLDER . '/' . AURA_SMARTY_MASTERPAGE_DEFAULT . self::smarty_ext)) {
						$message = 'Master page default (' . AURA_SMARTY_MASTERPAGE_DEFAULT . self::smarty_ext . ') not found';
						echo $message;
						Helper::log('framework', $message);
						exit;
					}
				}
			}

			if($master)
			{	
				if (AURA_SMARTY_MASTERPAGES_FOLDER) {
					$display = 'extends:' . AURA_SMARTY_MASTERPAGES_FOLDER . '/' . $master . self::smarty_ext . '|' . $this->template_folder . $template . self::smarty_ext;
					$this->view->display($display);
				}
				else
					$this->view->display('extends:' . $master . self::smarty_ext . '|' . $this->template_folder . $template . self::smarty_ext);
			}
			else {
				if (AURA_SMARTY_MASTERPAGE_DEFAULT) {
					$display = "extends:" . AURA_SMARTY_MASTERPAGES_FOLDER . '/' . AURA_SMARTY_MASTERPAGE_DEFAULT . self::smarty_ext . '|' . $this->template_folder . $template . self::smarty_ext;
					$this->view->display($display);	
				}
				else
					$this->view->display($this->template_folder . $template . self::smarty_ext);
			}
		}
		
		/**
		 * Get template content
		 * @param object $template : Template name
		 * @return String
		 */
		public function template_get_content($template)
		{
			return $this->view->fetch($this->template_folder . $template . self::smarty_ext);
		}
		
		/**
		 * Get controller object
		 * @param object $name : Controller name
		 * @return Object
		 */
		public function controller($name)
		{
			$controller = ucwords(strtolower($name)) . 'Controller';
			$path_controller = AURA_APP . DS . Aura::$app . DS . 'controllers' . DS . $controller . '.php';
			
			if (!is_array($this->instanceController))
				$this->instanceController = Array();
			
			if (is_file($path_controller)) {
				if (!array_key_exists($controller, $this->instanceController)) {
					$this->required_file($path_controller);
					$object = new $controller;
					$this->instanceController[strtolower($controller)] = $object;
				}
				
				return $this->instanceController[strtolower($controller)];
			}
			else {
				Helper::log('framework', "Controller '$name' not found");
				return false;
			}
				
		}
	}
?>