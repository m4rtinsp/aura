<?php
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', dirname(dirname(dirname(__FILE__))));
	
    $action = isset($_POST['action']) ? $_POST['action'] : null;

	if (!$action) {
		echo json_encode(array('status' => false));
		exit;
	}
	
	switch ($action)
	{
		case 'create_app':
			$app = isset($_POST['app']) ? $_POST['app'] : null;
			
			if (!$app) {
				echo json_encode(array('status' => false));
				exit;
			}
			
			$result = copyr(ROOT . DS . 'core' . DS . 'system' . DS . 'application-model', ROOT . DS . 'app' . DS . $app);

			echo json_encode(array('status' => $result['status'], 'message' => $result['message']));
			break;
		
		case 'create_controller':
			$app = isset($_POST['app']) ? $_POST['app'] : null;
			$name = isset($_POST['name']) ? $_POST['name'] : null;
		
			if (!$app oR !$name) {
				echo json_encode(array('status' => false));
				exit;
			}
			
			$result = create_controller($app, $name);
			
			echo json_encode(array('status' => $result['status'], 'message' => $result['message']));
			break;
		
		case 'create_models':
			$app = isset($_POST['app']) ? $_POST['app'] : null;
			$model_name = isset($_POST['model_name']) ? $_POST['model_name'] : null;

			if (!$app) {
				echo json_encode(array('status' => false));
				exit;
			}
			
			$result = create_models($app, $model_name);
			
			echo json_encode(array('status' => $result['status'], 'message' => $result['message']));	
			break;
			
		default:
			echo json_encode(array('status' => false));
	}
	
	function copyr($source, $dest)
	{
		$status = true;
		$message = null;

		// Copy file
	   	if (is_file($source)) {
	      $rs = copy($source, $dest);
	      chmod($dest, 0777);
	      return $rs;
	  	}

	  	
	   	// Create a directory
	   	if (!is_dir($dest)) {
			$rs = mkdir($dest);

			if (!$rs) {
				$status = false;
				$message = 'Permission denied to create ' . $dest;
			}

	      	// Loop on folder
			$dir = dir($source);
			while (false !== $entry = $dir->read()) {
			  // Jump ., .. and .svn
			  if ($entry == '.' || $entry == '..' || $entry == '.svn' || $entry == '.git')
			     continue;
			  
			  // Copy all files in drectory
			  if ($dest !== "$source/$entry") {
			     copyr($source . DS . $entry, $dest . DS. $entry);
			  }
			}

			$dir->close();
	  	}
	   
	   return array('status' => $status, 'message' => $message);
	}
	
	function create_controller($app, $name)
	{
		if (!is_dir(ROOT . DS . 'app' . DS . $app))
			return array('status' => false, 'message' => 'invalid app');
		
		$file_path = ROOT . DS . 'app' . DS . $app . DS . 'controllers' . DS . ucfirst($name) . 'Controller.php';
			
		if (!file_exists($file_path)) {
			$file_content = file_get_contents(ROOT . DS . 'core' . DS . 'system' . DS . 'code-model' . DS . 'framework-controller.php');
			$file_content = str_replace(array('%controller%'), array(ucfirst($name)), $file_content);
			
			// Controller
			$fp = fopen($file_path, "w");
			$rs = fwrite($fp, $file_content);
			fclose($fp);
			chmod($file_path, 0777);

			// Folder in views
			mkdir(ROOT . DS . 'app' . DS . $app . DS . 'views' . DS . 'templates' . DS . strtolower($name));
			$fp = fopen(ROOT . DS . 'app' . DS . $app . DS . 'views' . DS . 'templates' . DS . strtolower($name) . DS . 'index.tpl', "w");

			if (!$rs)
				return array('status' => false, 'message' => 'Permission denied in ' . DS . 'app' . DS . $app . DS . 'controllers');

			fwrite($fp, null);
			fclose($fp);
			
			return array('status' => true, 'message' => ucfirst($name) . 'Controller');
		}
		
		return array('status' => true, 'message' => 'this controller already exists');
	}
	
	function create_models($app, $model_name)
	{
		$models = array();

		if (!$model_name) {
			if (is_file(ROOT . DS . 'app' . DS . $app . DS . 'config' . DS . 'database.php'))
				require_once ROOT . DS . 'app' . DS . $app . DS . 'config' . DS . 'database.php';
			else
				return array('status' => false, 'message' => 'invalid app');
				
			$database_info = get_class_vars("APP_DATABASE");
			$ref = APP_DATABASE::$set;
			
			if (!isset($database_info[$ref]))
				return array('status' => false, 'message' => 'variable configuration not found');
			
			$database_name = $database_info[$ref]['database'];
			$driver = $database_info[$ref]['driver'];
			$host = $database_info[$ref]['host'];
			$login = $database_info[$ref]['login'];
			$password = $database_info[$ref]['password'];
			$prefix = $database_info[$ref]['prefix'];
			
			if ($driver != 'mysql')
				return array('status' => false, 'message' => 'invalid driver');
			
			try {
				$pdo = new PDO("$driver:host=$host;dbname=$database_name;charset=UTF-8", "$login", "$password");
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				
				// Check if database exists
				$sql = $pdo->prepare("SHOW TABLES");
				$sql->execute();
			}
			catch ( PDOException $Exception ) {
				return array('status' => false, 'message' => $Exception->getMessage());
			}
			
			$sql = $pdo->prepare("SHOW TABLES");
			$sql->execute();
			$tables = $sql->fetchAll( PDO::FETCH_ASSOC );
			
			if (empty($tables))
				return array('status' => false, 'message' => 'no tables in this database');
			
			foreach ($tables as $key => $table) {
				$table = $table["Tables_in_$database_name"];
				$real_table_name = $table;
				
				if (strpos($table, '_') !== false) {
					$table_parts = explode('_', $table);
					$table = null;

					foreach ($table_parts as $part)
						$table .= ucwords($part);
				}
				
				// Get primary key field
				$sql = $pdo->prepare("SHOW COLUMNS FROM $real_table_name");
				$sql->execute();
				$fields = $sql->fetchAll( PDO::FETCH_ASSOC );
				$table_key = null;

				if ($fields) {
					foreach ($fields as $field) {
						if ($field['Key'] == 'PRI')
							$table_key .= $field['Field'] . ', ';
					}

					$table_key = substr($table_key, 0, -2);
				}

				$file_path = ROOT . DS . 'app' . DS . $app . DS . 'models' . DS . ucfirst($table) . 'Model.php';
				
				if (!file_exists($file_path)) {
					$file_content = file_get_contents(ROOT . DS . 'core' . DS . 'system' . DS . 'code-model' . DS . 'framework-model.php');
					$file_content = str_replace(array('%model%', '%table_name%', '%table_key%'), array(ucfirst($table), $real_table_name, $table_key), $file_content);
					
					array_push($models, ucfirst($table) . 'Model');
					
					$fp = fopen($file_path, "w");
					$rs = fwrite($fp, $file_content);
					fclose($fp);
					chmod($file_path, 0777);

					if (!$rs)
						return array('status' => false, 'message' => 'Permission denied in ' . DS . 'app' . DS . $app . DS . 'models');
				}
			}
		} else {
			$file_path = ROOT . DS . 'app' . DS . $app . DS . 'models' . DS . ucfirst($model_name) . 'Model.php';
			
			if (!file_exists($file_path)) {
				$file_content = file_get_contents(ROOT . DS . 'core' . DS . 'system' . DS . 'code-model' . DS . 'framework-model.php');
				$file_content = str_replace(array('%model%', '%table_name%', '%table_key%'), array(ucfirst($model_name), '', ''), $file_content);
				
				array_push($models, ucfirst($model_name) . 'Model');

				$fp = fopen($file_path, "w");
				$rs = fwrite($fp, $file_content);
				fclose($fp);
				chmod($file_path, 0777);

				if (!$rs)
					return array('status' => false, 'message' => 'Permission denied in ' . DS . 'app' . DS . $app . DS . 'models');
			}
			else
				return array('status' => true, 'message' => 'this model already exists');
		}
		
		return array('status' => true, 'message' => $models);
	}
?>