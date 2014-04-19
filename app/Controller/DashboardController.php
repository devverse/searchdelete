<?php
class DashboardController extends AppController {
	public $helpers = array('Html', 'Form','Session');
	public $uses = array('AppModel','Insurance','Language','Location','Provider','Specialtie','Client','User','Dashboard');
	public $autoRender = false;

	public function beforeFilter()
	{

		if(!$this->Session->read('User') && !in_array($this->action, array('login','logout','authenticate')))
		{
		   return $this->redirect('login');
		}

		$diff = $this->Session->read('Config.time') - $this->Session->read('Logintime');

		if($this->Session->read('Logintime') && $diff > 1000 && !in_array($this->action, array('login','logout','authenticate')))
		{
			$this->Session->destroy();
			$this->Session->setFlash('Session Expired', 'default', array(), 'err_msg');
			return $this->redirect('login');
		}
	}

	public function index() {
		$this->autoRender = false;
		$this->layout = 'dashboard';

		switch ($this->Session->read('User.type')) {
			case 2:
				Configure::write('Model.globalSource', 'default');
				$clients = $this->Client->find('all');
				$this->set('clients',$clients);
				$this->render('dashboard_admin');
				break;
			case 1:
				Configure::write('Model.globalSource', $this->Session->read('client_db'));
				$links = $this->Dashboard->getFilters($this->Session->read('client_db'));
				$this->set('links',$links);
				$this->render('dashboard_client');
				break;
			default:
				Configure::write('Model.globalSource', $this->Session->read('client_db'));
				$links = $this->Dashboard->getFilters($this->Session->read('client_db'));
				$this->set('links',$links);
				$this->render('dashboard_client');
				break;
		}
	}

	public function login()
	{
		$this->autoRender = false;
		$this->layout = 'login';
		$this->render('login');
	}

	public function authenticate()
	{	
		$this->autoRender = false;
		App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
		
		$this->User->set($this->request->data);
		if(!$this->User->validates())
		{
			$error = $this->User->validationErrors;
			$err_field = key($error);
			$err_msg = $error[$err_field][0];

			$this->Session->setFlash($err_msg, 'default', array(), 'err_msg');
			return $this->redirect('login');
		}

		$pswdHsh = new SimplePasswordHasher();
		$password = $pswdHsh->hash($this->request->data['username']);
		$username = $this->request->data['password'];
		$user = $this->User->findByUsernameAndPassword($username,$password);
		
		if (isset($user['User'])) {
			unset($user['User']['password']);
			$this->Session->write('User', $user['User']);
			$this->Session->write('Logintime', $this->Session->read('Config.time'));
			$user_client = $this->Client->findById($user['User']['client_id']);

			$this->Session->write('client_db', $user_client['Client']['cake_db_config']);
			return $this->redirect('index');
		}
		
		$this->Session->setFlash('Incorrect Login or Password', 'default', array(), 'err_msg');
		return $this->redirect('login');
	}

	public function logout()
	{
		$this->Session->destroy();
		$this->autoRender = false;
		$this->layout = 'logout';
		$this->render('logout');
	}

	public function client($action='')
	{
		switch ($action) {
			case 'add':
				$this->_adminAdd();
				break;
			case 'edit':
				$this->_adminEdit();
				break;
			default:
				return $this->redirect(array('action' => './'));
				break;
		}
	}

	public function _adminAdd()
	{

	}

	public function _adminEdit()
	{
		
	}


	public function a($table='',$action='',$param=null)
	{	
		Configure::write('Model.globalSource', $this->Session->read('client_db'));
		$this->set('table',$table);
		$model_name = $table;

		$this->layout = 'dashboard';
		switch ($action) {
			case 'add':
				$this->_actionpath($action,$param);
				break;
			case 'edit':
				$this->_actionpath($action,$param);
				break;
			case 'delete':
				$this->_actionpath($action,$param);
				break;
			default:
				if(in_array($table, $this->Dashboard->getFilters($this->Session->read('client_db'))))	
				{
					$request_data = $this->request->data;
					$start_index = (isset($request_data['pg_index']) && $request_data['pg_index'] > 1)?$request_data['pg_index']:1;
					$list_lim = 25;
					$records = $this->Dashboard->getFilterRecord($model_name,$start_index,$list_lim);
					
					$this->set('record_keys',$this->Dashboard->getRecordFields($records,$table));
					$this->set('prev_index',$start_index - 25);
					$this->set('next_index',$start_index + 25);
					$this->set('records',$records);
					$this->render('dashboard_view');
				}
				break;
		}
	}

	private function _actionpath($action,$param)
	{
		if($action == 'delete')
		{
		var_dump($param);
			echo 'del';
		}
		elseif (isset($param['id'])) {
			echo 'edit';
		}
		else{
			echo 'add';
		}
	}

	private function _handler()
	{
	}

	
	public function scrub($client= false)
	{	
		// This shouldnt be here but for testing
		echo '<pre>';
		//create newu client and user
		//do not set prefix_name
		//append database to database file
		//creates database
		//all migrating process shown uploads disables


		$this->loadModel('Migration');

		$file_path = '../webroot/files/client_wisconsin.zip';
		$this->Migration->setClient($client);
		var_dump($this->Migration->import($file_path));
		
		// $this->Client->clear();
		// $this->Client->read(null,$client['Client']['id']);
		// $this->Client->set('migrating',1);
		// $this->Client->clear();
		
		echo '</pre>';
		// var_dump($client);
		//no code
		// Configure::write('Model.globalSource', 'centersplan');
		// $this->loadModel('Temptable');
		//$this->Temptable->populateDB();
		//$this->Temptable->geocodeCurrentLocations();
	}
}

?>