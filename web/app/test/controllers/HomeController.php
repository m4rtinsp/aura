<?php
    class HomeController extends AppController
	{
		public function index()
		{
			//$data = array('data' => array('author' => utf8_decode('Nádia Martins'), 'title' => 'test', 'description' => 'my text'));
			//var_dump($this->Tips->set($data));

			/*
			$this->Tips->setById(11, $data);
			
			$data = array('data' => array('status' => 0));
			$this->Users->setAll($data);

			
			*/

			//var_dump(Aura::$controller);
			//var_dump(Aura::$action);

			// include
			//$this->vendors('twitter');
			//var_dump(a());

			// return objet
			//$twitter = $this->vendors('twitter/twitter');
			//var_dump($twitter->index());

			//$data = array('data' => array('author' => 'Juan', 'title' => 'New', 'description' => 'wow'));
			//$action = $this->Tips->set($data);
			
			//$tips = $this->Tips->getAll(array('where' => array("author LIKE 'Paulo%'"), 'limit' => 5, 'order' => 'author'));

			//$this->Tips->test_rollback();

			//$ts = $this->Tips->getByAuthor('Paulo Martins', array('where' => 'id <> 11'));
			//var_dump($ts);

			$cars = $this->Car->getAll();
			$this->locale('pt_br');

			//$this->set('tips', $tips['data']);
			$this->set('cars', $cars['data']);
			$this->show('index', false);
		}
	}
?>