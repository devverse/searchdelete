<?php
class DashboardController extends AppController {
	public $helpers = array('Html', 'Form','Session');
	public $uses = array('AppModel','Insurance','Language','Location','Provider','Specialtie','Client','User');
	public $autoRender = false;

	public function beforeFilter()
	{

		if(!$this->Session->read('User') && !in_array($this->action, array('login','logout','authenticate')))
		{
		   return $this->redirect('login');
		}

		$diff = $this->Session->read('Config.time') - $this->Session->read('Logintime');

		if($this->Session->read('Logintime') && $diff > 360 && !in_array($this->action, array('login','logout','authenticate')))
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
				$this->render('dashboard_admin');
				break;
			case 1:
				$this->render('dashboard_client');
				break;
			default:
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

	public function provider()
	{
		echo 'provider';
	}

	public function location()
	{
		echo 'location';
	}
	public function specialty()
	{
		echo 'specialty';
	}

	public function insurance()
	{
		echo 'insurance';
	}

	public function language()
	{
		echo 'language';
	}
}

?>