<?php
	/**
	 * Aura PHP Framework - Locale class
	 * 
	 * @author	Paulo Martins <phmartins6@gmail.com>
	 */
	
    abstract class Locale
	{
		/**
		 * Return array of data
		 * @param object $language : Language (this parameter can be replaced by a session variable of same name)
		 * @return String
		 */
		static function set($language)
		{
			$data = array();

			if (!$language) {
				if(isset($_SESSION['language']))
					$language = $_SESSION['language'];
				else {
					return "language not found";
					exit;    
				}
			}
			
			if (!is_dir(AURA_APP . DS . Aura::$app . DS . 'locale' . DS . $language))
				return $data;
			
			$pointer = opendir(AURA_APP . DS . Aura::$app . DS . 'locale' . DS . $language);
			
			while ($itens = readdir($pointer)) {
				$file = AURA_APP . DS . Aura::$app . DS . 'locale' . DS . $language . DS . $itens;
				$name = reset(explode('.', $itens));

				if (is_file($file)) {
					$locale = parse_ini_file($file);
					$data[$name] = $locale;
				}
			}

			return $data;
		}
	}
?>