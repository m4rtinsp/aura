<?php
	/**
	 * Aura PHP Framework - Helper class
	 * 
	 * @author	Paulo Martins <phmartins6@gmail.com>
	 */
	
    class Helper
	{
		const root = aura_root;
		const app_root = app_root;
		const http_root = aura_http_root;

		/**
		 * Date format
		 * @param string $format
		 * @param object $date
		 * @return object
		 */
		static public function date_format($format, $date)
		{
			return date($format, strtotime($date));
		}
		
		/**
		 * Trim the elements of an array
		 * @param object $array : Array of data
		 * @return Array
		 */
		static public function array_trim($array) {
			foreach($array as $key => $value) {
				if (is_array($value))
					$array[$key] = self::array_trim($value);
				else
					$array[$key] = trim($value);
			}
			
			return $array;
		}
		
		/**
		 * Encode or decode the elements an array in utf8 
		 * @param object $array : Array of data
		 * @param string $action : encode or decode
		 * @return Array
		 */
		static public function array_utf8($array, $action) {
			$action = 'utf8_' . $action;
			
			if (is_array($array)) {
				foreach($array as $key => $value) {
					if (is_array($value))
						$array[$key] = self::array_utf8($value, $action);
					elseif (is_object($value)) {
						foreach ($value as $label => $val) {
							if (is_string($val))
								$array[$key]->$label = $action($val);
						}
					}
					else {
						if (is_string($value))
							$array[$key] = $action($value);
					}
						
				}
			}
			elseif (is_object($array)) {
				foreach ($array as $label => $val) {
					if (is_string($val))
						$array->$label = $action($val);
				}
			}
			
			return $array;
		}
		
		/**
		 * Inserts a tab in the string example: 12345678 = 1234-5678
		 * @param string $string : String to be modified
		 * @param int $position [optional] : Tab position in the string
		 * @param string $tab [optional] : Tab
		 * @return String
		 */
		static public function partial_string($string, $position = 1, $tab = "-") {
			return substr($string, 0, $position-1) . $tab . substr($string, $position-1);
		}
		
		/**
		 * Creates an md5 hash using a string and a token (config / settings.php)
		 * @param string $string
		 * @return String
		 */
		static public function md5($string) {
			return md5($string . AURA_MD5_TOKEN);
		}
		
		/**
		 * Remove tags from a string
		 * @param string $string
		 * @param array $tags : Tags to be removed
		 * @return String
		 */
		static public function remove_tag_from_string($string, $tags)
		{
			if (!$string)
				return false;
			
			if(is_array($tags) && count($tags) > 0) {
				foreach ($tags as $tg)
					$string = preg_replace("/<".$tg."[^>]+\>/i", "", $string);
			}

			return $string;
		}
		
		/**
		 * Generator logs (log folder in the root of the framework)
		 * @param string $file_name :  Filename
		 * @param string $text : Contents of the log
		 * @return Bool 
		 */
		static public function log($file_name, $text, $app = true)
		{
			$app = $app ? Aura::$app . '_' : '';
			$file_name = $app . $file_name;
			$log = fopen(AURA_ROOT . DS . AURA_LOGS_FOLDER . DS . $file_name . '.log', 'a'); 
			fwrite($log, "[".date("r")."] $text\r\n");
			 
			return fclose($log);
		}
		
		/**
		 * Password generator
		 * @param int $lenght : Password length
		 * @param bool $big : Enter uppercase characters
		 * @param bool $small : Enter lowercase characters
		 * @param bool $numbers : Enter numbers
		 * @param bool $codes : Enter codes
		 * @return String
		 */
		static public function password_generator($lenght, $big, $small, $numbers, $codes) {
			$mai = "ABCDEFGHIJKLMNPQRSTUWXYZ";
			$min = "abcdefghijklmnpqrstuwxyz";
			$num = "0123456789";
			$cod = "!@#%&*";
			
			$base = "";
			
			$base .= ($big) ? $mai : '';
		    $base .= ($small) ? $min : '';
		    $base .= ($numbers) ? $num : '';
		    $base .= ($codes) ? $cod : '';
		 
		    srand((float) microtime() * 10000000);
		    
			$pass = "";
			
		    for ($i = 0; $i < $lenght; $i++)
		        $pass .= substr($base, rand(0, strlen($base)-1), 1);
		    
		    return $pass;
		}
		
		/**
		 * Random number generator
		 * @param int $lenght : Number length
		 * @return int
		 */
		static public function generate_random_number($lenght)
		{
			$base = "0123456789";
			$pass = "";
			
			for ($i = 0; $i < $lenght; $i++)
		        $pass .= substr($base, rand(0, strlen($base)-1), 1);
		    
		    return $pass;
		}
		
		/**
		 * Verify if there is a ajax request
		 * @return Bool 
		 */
		static function is_ajax_request() 
	    { 
	        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest'; 
	    }
		
		/**
		 * Create a array of filters
		 * @param array $fields
		 * @return array
		 */
		static function framework_model_filter($fields)
		{
			if ($fields) {
				$filtro = array();
				
				foreach ($fields as $field => $condition)
				
				if (isset($_GET[$field]))
					$filtro[$field] = array('condition' => $condition, 'value' => $_GET[$field]);
			}
				
			return isset($filtro) ? $filtro : false;
		}
		
		/**
		 * Send mail (mail function)
		 * @param string $from
		 * @param string $to
		 * @param string $subject
		 * @param text $message
		 * @param string $mime_version [optional] : default MIME-Version: 1.1
		 * @param string $content_type [optional] : defatul Content-type: text/plain; charset=iso-8859-1
		 * @param bool $return [optional] : Enables e-mail response
		 * @return Bool
		 */
		static public function simple_send_mail($from, $to, $subject, $message, $mime_version = "MIME-Version: 1.1", $content_type = "Content-type: text/plain; charset=iso-8859-1", $return = true)
		{
			$headers = "$mime_version\n";
			$headers .= "$content_type\n";
			$headers .= "From: $from\n"; // remetente
			
			if ($return)
				$headers .= "Return-Path: $from\n"; // return-path
			
			$send = mail($to, $subject, $message, $headers);
			
			return $send;
		}
	}
?>