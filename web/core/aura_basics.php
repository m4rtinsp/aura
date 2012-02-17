<?php
	/**
	 * Aura PHP Framework - Aura Basics class
	 * @author	Paulo Martins <phmartins6@gmail.com>
	 */
	
    class aura_basics
	{
		/**
		 * Manager messaging framework
		 * @param object $who [optional]
		 * @param object $current [optional]
		 * @return String
		 */
		protected function display($who = false, $current = null)
		{
			$title = 'Aura PHP Framework';
			$css = aura_http_root . 'core/system/assets/web/aura_framework.css';
			$content = file_get_contents(AURA_CORE_SYSTEM_TEMPLATE . DS . 'framework-error.html');
			$info = '';
			
			if ($who) {
				switch ($who)
				{
					case 'action_error':
						$type = 'error';
						$title_display = $title . ' - Error';
						$message = 'Action not found';
						$info = $this->getinfo(array('Action called' => $current));
						$this->log("Action '$current' not found");
						break;
						
					case 'controller_error':
						$type = 'error';
						$title_display = $title . ' - Error';
						$message = 'Controller not found';
						$info = $this->getinfo(array('Controller called' => $current));
						$this->log("Controller '$current' not found");
						break;
				}
			}
			
			$data_label = array(
				'%type%', '%title%', '%title_display%', '%path_css%', '%message%', '%info%'
			);
			
			$data = array(
				$type,
				$title,
				$title_display,
				$css,
				$message,
				$info
			);
			
			echo str_replace($data_label, $data, $content);
		}
		
		/**
		 * Create list of parameters
		 * @param object $data
		 * @return String
		 */
		private function getinfo($data)
		{
			$ul_init = "<ul>";
			$ul_end = "</ul>";
			$li = null;
			
			if ($data) {
				foreach ($data as $label => $info)
					$li .= "<li><label>$label:</label>$info</li>";
			}
			
			if ($li)
				return $ul_init . $li . $ul_end;
		}
		
		/**
		 * Generates logs of the core
		 * @param object $text
		 * @param object $ref [optional]
		 * @return 
		 */
		protected function log($text, $ref = null)
		{
			if (AURA_LOGS)		
				Helper::log('framework', $text, false);
		}
		
		/**
		 * Test function
		 * @return String
		 */
		protected function test()
		{
			echo "============ Framework test =========";
			echo '<br/>';
			echo 'App: ' . Aura::$app;
			echo '<br/>';
			echo 'App default: ';
			echo $this->app_default ? 'true' : 'false';
			echo '<br/>';
			echo 'Controller: ' . Aura::$controller;
			echo '<br/>';
			echo 'Controller default: ';
			echo $this->controller_default ? 'true' : 'false';
			echo '<br/>';
			echo 'Action: ' . Aura::$action;
			echo '<br/>';
			echo 'Action default: ';
			echo $this->action_default ? 'true' : 'false';
			echo '<br/>';
			echo 'Params: ';
			if ($this->params) {
				foreach($this->params as $param)
					echo $param . ', ';
			}
			else
				echo 'null';
			echo '<br/>';
			echo "=================================";
		}
	}
?>