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
		$this->set('title', 'Search Directory for '.ucfirst($client['Client']['name']));
		$this->set('asset_folder', $client['Client']['asset_folder_name']);

		$this->set('client_name', $client['Client']['name']);
		$this->layout = 'search';
		$this->render($client['Client']['view_prefix_name'].'search');
	}

	public function result($client)
	{
		Configure::write('Model.globalSource', $client['Client']['cake_db_config']);
		$this->loadModel('Search');
		$request_data = $this->request->data;
		$request_data['start'] = isset($request_data['start']) && $request_data['start']>=0 ? $request_data['start']:0;
		$this->Search->set($request_data);

		if($this->Search->validates())
		{
			$results = $this->Search->getResults($request_data['zipcode'],$request_data['distance'],$request_data['start'],$request_data);
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
		$this->set('client_name', $client['Client']['name']);
		$this->set('srch_filter',$request_data);
		$this->set('results', $results['providers']);
		$this->set('locations', $results['locations']);
		$this->set('coor', $results['coor_array']);
		$this->set('title', 'Search Results for '.ucfirst($client['Client']['name']));
		$this->set('asset_folder', $client['Client']['asset_folder_name']);

		$this->layout = 'result';
		$this->render($client['Client']['view_prefix_name'].'result');
	}

	public function view()
	{
		$this->autoRender = false;

		$this->layout = 'search';
		$this->render('view1');
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