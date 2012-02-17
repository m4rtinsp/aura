<?php
	/**
	 * Aura PHP Framework
	 * 
	 * @author			Paulo Martins <phmartins6@gmail.com>
	 * @copyright		2010 ~ 2011
	 * @version			Framework 1.4.0 / PHP > 5.2
	 */
	
	define('AURA_FRAMEWORK_VERSION', '1.4.0');
	define('AURA_PHP_MIN_VERSION', '5.2+');
	
	// AURA's ROOT DIRECTORY
	define('DS', DIRECTORY_SEPARATOR);
	define('APP_DIRECTORY', 'app');
	define('CORE_DIRECTORY', 'core');
	define('LIBS_DIRECTORY', 'libs');
	define('SMARTY', 'Smarty-3.0.7');
	
	define('AURA_ROOT', dirname(__FILE__));
	define('AURA_APP', AURA_ROOT . DS . APP_DIRECTORY);
	define('AURA_CORE', AURA_ROOT . DS . CORE_DIRECTORY);
	define('AURA_CORE_SYSTEM_TEMPLATE', AURA_CORE . DS . 'system' . DS . 'template');
	define('AURA_SMARTY', AURA_CORE . DS . 'libs' . DS . SMARTY);
	
	require AURA_ROOT . DS . 'preferences.php';
	require AURA_CORE . DS . 'init.php';
?>