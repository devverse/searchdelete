<?php
class DashboardController extends AppController {
	public $helpers = array('Html', 'Form','Session');
	public $uses = array('AppModel','Fullrecord','Dashboard','User','Client','ClientUsers');
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
				$this->_loadClientDash();
				break;
			default:
				$this->_loadClientDash();
				break;
		}
	}

	public function login()
	{
		$this->autoRender = false;
		$this->layout = 'login';
		$this->render('login');
	}

	public function logout()
	{
		$this->Session->destroy();
		$this->Session->setFlash('You have been logged out', 'default', array(), 'err_msg');
		return $this->redirect('login');
	}

	public function _loadClientDash()
	{
		Configure::write('Model.globalSource', $this->Session->read('client_db'));
		try {
			$fields = $this->Dashboard->getRecordFields('fullrecords');
			$this->set('fields',$fields);
			$this->set('field_count',count($fields));
		} catch (Exception $e) {
			$this->set('fields',array());
			$this->set('field_count',0);
		}

		$request_data = $this->request->data;

		$update_records = $this->Fullrecord->getForUpdate($request_data);
		if($update_records)
		{
			$this->set('editrecords',$update_records);
		}

		if(!$update_records&&isset($request_data['Search']))
			$this->Session->setFlash("'{$request_data['Search']}' was not found.", 'default', array(), 'err_msg');
		$this->render('dashboard_client');
	}

	/**
	 * TODO Throw in model
	 */
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
		$password = $pswdHsh->hash($this->request->data['password']);
		$username = $this->request->data['username'];
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

	/**
	 * TODO throw in model 
	 */
	public function upload()
	{
		Configure::write('Model.globalSource', $this->Session->read('client_db'));
		$client_db 		= $this->Session->read('client_db');
		$user 			= $this->Session->read('User');
		$upload_array 	= $_FILES;

		if($client_db&&$user&&is_array($upload_array)&&isset($upload_array['file']['tmp_name'])&&$upload_array['file']['tmp_name']!='')
		{
			$this->loadModel('Migration');

			$this->Migration->setClient($user['client_id']);

			try {
				//uploads zip file
				$file_path = '../webroot/files/client_'.$this->Migration->client_info['Client']['name'].'.zip';
				copy($upload_array['file']['tmp_name'], $file_path);

				// Delete existing records
				$this->Migration->truncateTable();

				//import action
				$resp = $this->Migration->import($file_path);

				// rm zip provider txt and folder
				unlink($file_path);
				unlink('../webroot/files/'.$this->Migration->client_info['Client']['name'].'/providers.txt');
				rmdir('../webroot/files/'.$this->Migration->client_info['Client']['name']);

			} catch (Exception $e) {
				$resp = array('status'=>false,'response'=>'Something went wrong migrating db. Contact the web administrator');
			}
			
			if($resp['status'])
				$this->Session->setFlash($resp['response'], 'default', array(), 'succ_msg');
			else
				$this->Session->setFlash($resp['response'], 'default', array(), 'err_msg');

			return $this->redirect('index');
		}else{
			$this->Session->setFlash('There was a problem with the request. You have been logged out.', 'default', array(), 'err_msg');
			return $this->redirect('login');
		}
	}

	public function updateprovider()
	{
		Configure::write('Model.globalSource', $this->Session->read('client_db'));

		$request_data = $this->request->data;

		$this->Fullrecord->set($request_data);

		if($this->Fullrecord->validates())
		{
			$this->Fullrecord->save($request_data);
			if($this->Fullrecord->id)
				$resp = array('status'=>true);
			else
				$resp = array('status'=>false,'response'=>'Record was not saved');
		}
		else
		{
			$error = $this->Fullrecord->validationErrors;
			$err_field = key($error);
			$err_msg = $error[$err_field][0];
			$resp = array('status'=>false,'response'=>$err_msg);
		}

		if($resp['status'])
			$this->Session->setFlash('Provider Record Updated', 'default', array(), 'succ_msg');
		else
			$this->Session->setFlash($resp['response'], 'default', array(), 'err_msg');
		return $this->redirect('index');
	}

	public function addprovider()
	{
		Configure::write('Model.globalSource', $this->Session->read('client_db'));

		$request_data = $this->request->data;

		$this->Fullrecord->set($request_data);

		if($this->Fullrecord->validates())
		{
			$request_data['longitude_str'] = $request_data['longitude'];
			$request_data['latitude_str'] = $request_data['latitude'];

			$this->Fullrecord->create();
			$this->Fullrecord->save($request_data);
			if($this->Fullrecord->id)
				$resp = array('status'=>true);
			else
				$resp = array('status'=>false,'response'=>'Record was not saved');

		}
		else
		{
			$error = $this->Fullrecord->validationErrors;
			$err_field = key($error);
			$err_msg = $error[$err_field][0];
			$resp = array('status'=>false,'response'=>$err_msg);
		}

		if($resp['status'])
			$this->Session->setFlash('Provider Record Added', 'default', array(), 'succ_msg');
		else
			$this->Session->setFlash($resp['response'], 'default', array(), 'err_msg');
		return $this->redirect('index');
	}

	public function deleteprovider()
	{
		Configure::write('Model.globalSource', $this->Session->read('client_db'));

		$request_data = $this->request->data;
		$id = isset($request_data['id'])?$request_data['id']:false;
		if($id && is_numeric($id)&&$id!=false){
			if($this->Fullrecord->delete($id))
				$this->Session->setFlash('Provider record deleted', 'default', array(), 'succ_msg');
			else
				$this->Session->setFlash('Provider record wasnt deleted', 'default', array(), 'err_msg');
		}else{
			$this->Session->setFlash('Incorrect id format', 'default', array(), 'err_msg');
		}
		
		return $this->redirect('index');
	}

	public function addClient()
	{
		Configure::write('Model.globalSource', 'default');
		$status = false;
		$request_data = $this->request->data;
		
		$this->ClientUsers->set($request_data);

		if($this->ClientUsers->validates())
		{
			$status = $this->ClientUsers->addClientSettings();
		}
		else
		{
			$error = $this->ClientUsers->validationErrors;
			$err_field = key($error);
			$err_msg = $error[$err_field][0];
			$this->Session->setFlash($err_msg, 'default', array(), 'err_msg');
			return $this->redirect('index');
		}

		if($status)
		{
			$this->Session->setFlash('New Client Added', 'default', array(), 'succ_msg');
		}
		else
		{
			$this->Session->setFlash('There was an error adding a new client', 'default', array(), 'err_msg');
		}
		return $this->redirect('index');
	}

	public function updateClient()
	{

		Configure::write('Model.globalSource', 'default');
		$status = false;
		$request_data = $this->request->data;

		$status = $this->ClientUsers->updateClientSettings($request_data);

		if($status)
		{
			$this->Session->setFlash('Client settings saved', 'default', array(), 'succ_msg');
		}
		else
		{
			$this->Session->setFlash('Client setting was not saved. An error occured', 'default', array(), 'err_msg');
		}
		return $this->redirect('index');
	}
}

?>