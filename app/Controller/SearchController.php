<?php
class SearchController extends AppController {
	public $helpers = array('Html', 'Form','Session');
	public $uses = array('AppModel','Insurance','Language','Location','Provider','Specialtie','Client');

	public function index($client,$action='search') {
		$this->autoRender = false;

		if(isset($client))
			$client = $this->Client->findByName($client);
		else
			$client = false;

		switch ($action) {
			case 'search':
				$this->search($client);
				break;
			case 'result':
				$this->result($client);
				break;
			case 'scrub':
				$this->scrub($client);
				break;
			default:
				$this->search();
				break;
		}
	}

	public function search($client=false)
	{	
		//globally define new database source
		Configure::write('Model.globalSource', $client['Client']['cake_db_config']);
		//$this->AppModel->setDataSource($client['Client']['cake_db_config']);
		
		$insurances 	= $this->Insurance->find('all');
		$languages 		= $this->Language->find('all');
		$locations 		= $this->Location->find('all');
		$providers 		= $this->Provider->find('all');
		$specialties 	= $this->Specialtie->find('all');

		$this->set('insurances', $insurances);
		$this->set('languages', $languages);
		$this->set('locations', $locations);
		$this->set('providers', $providers);
		$this->set('specialties', $specialties);

		$this->set('client_name', $client['Client']['name']);
		$this->layout = 'search';
		$this->render($client['Client']['view_prefix_name'].'search');
	}

	public function result($client)
	{
		Configure::write('Model.globalSource', $client['Client']['cake_db_config']);
		$this->loadModel('Search');
		$request_data = $this->request->data;
		$this->Search->set($request_data);
		if($this->Search->validates())
		{
			$results = $this->Search->getResults($request_data['zipcode'],$request_data['distance'],$request_data);
		}
		else
		{
			$error = $this->Search->validationErrors;
			$err_field = key($error);
			$err_msg = $error[$err_field][0];

			$this->Session->setFlash($err_msg, 'default', array(), $err_field);
			//$this->Session->setFlash(array($err_field,$err_msg));
			return $this->redirect(array('action' => $client['Client']['name']));
			//var_dump($this->Search->invalidFields());
			//exit;
		}

		$this->autoRender = false;
		$this->set('srch_filter',$request_data);
		$this->set('results', $results['providers']);
		$this->set('locations', $results['locations']);
		$this->set('coor', $results['coor_array']);
		
		$this->layout = 'search';
		$this->render($client['Client']['view_prefix_name'].'result');
	}

	public function view()
	{
		$this->autoRender = false;

		$this->layout = 'search';
		$this->render('view1');
	}

	public function scrub()
	{
		//no code
		Configure::write('Model.globalSource', 'centersplan');
		$this->loadModel('Temptable');
		//$this->Temptable->populateDB();
		//$this->Temptable->geocodeCurrentLocations();
	}
}

?>