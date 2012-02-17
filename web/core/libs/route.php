<?php
	/**
	 * Aura PHP Framework - Route class
	 * 
	 * @author	Paulo Martins <phmartins6@gmail.com>
	 */
	
    abstract class Route
	{
		static public $controller_routes;
		static public $action_routes;
		
		/**
		 * Defines routes
		 * @param object $type : Controller or action
		 * @param object $origin : Origin name
		 * @param object $destination : Destiny name
		 * @return 
		 */
		public function set($type, $origin, $destination)
		{
			if (!isset($origin) || !isset($destination)) {
				Helper::log('framework', 'Error: origin or destination not found', false);
				exit;
			}
			
			if ($type == 'controller')
				self::$controller_routes[$origin] = $destination;
			elseif ($type == 'action')
				self::$action_routes[$origin] = $destination;
		}
		
		/**
		 * Get routes
		 * @param object $type : Controller or action
		 * @return 
		 */
		public function get($type)
		{
			if ($type == 'controller')
				return isset(self::$controller_routes) ? self::$controller_routes : null;
			elseif ($type == 'action')
				return isset(self::$action_routes) ? self::$action_routes : null;
		}
	}
?>