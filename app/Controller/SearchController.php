<?php
class SearchController extends AppController {
	public $helpers = array('Html', 'Form','Session');
	public $uses = array('AppModel','Insurance','Language','Location','Provider','Specialtie','Network','Client','Providertype','Countie');

	public function index($client_orig_name,$action='search') {
		$this->autoRender = false;

		$network_ind=substr($client_orig_name, -1);
		if(is_numeric($network_ind))
			$client_name = substr($client_orig_name,0,-1);

		if(isset($client_name))
			$client_obj = $this->Client->findByName($client_name);
		else
			$action = 'error';

		switch ($action) {
			case 'search':
				$this->search($client_obj,$network_ind,$client_orig_name);
				break;
			case 'result':
				$this->result($client_obj,$client_orig_name);
				break;
			case 'scrub':
				$this->scrub($client_obj);
				break;
			case 'error':
				$this->error($client_orig_name);
				break;
			default:
				$this->search();
				break;
		}
	}

	public function error($client=false)
	{
		die('Client Name does not exist');
	}

	public function search($client=false,$ntwk_ind=false,$client_url_name='')
	{	
		//globally define new database source
		Configure::write('Model.globalSource', $client['Client']['cake_db_config']);

		$networks = $this->Network->find('all');
		if(is_numeric($ntwk_ind) && isset($networks[$ntwk_ind-1]['Network']))
			$this->set('network_name', $networks[$ntwk_ind-1]['Network']['name']);
		else
			$this->set('network_name', '');
		//$this->AppModel->setDataSource($client['Client']['cake_db_config']);
		if($client['Client']['migrating'] == 1 || $client['Client']['disable'] == 1 )
		{
			echo 'System is being updated. Please check back in a few minutes.';
			exit;
		}

		//BaseDropDown info
		$insurances 	= $this->Insurance->find('all');
		$languages 		= $this->Language->find('all');
		$locations 		= $this->Location->find('all');
		$providers 		= $this->Provider->find('all');
		$specialties 	= $this->Specialtie->find('all');
		$providertypes 	= $this->Providertype->find('all');
		$counties 		= $this->Countie->find('all');
		$this->set('counties', $counties);
		$this->set('providertypes', $providertypes);
		$this->set('insurances', $insurances);
		$this->set('languages', $languages);
		$this->set('locations', $locations);
		$this->set('providers', $providers);
		$this->set('specialties', $specialties);

		$this->set('title', 'Search Directory for '.ucfirst($client['Client']['name']));
		$this->set('client_name', $client['Client']['name']);
		$this->set('client_url_name', $client_url_name);

		// We are using just the search base layout / Dynamic is the asset_folder name
		$this->set('asset_folder', $client['Client']['asset_folder_name']);
		$this->layout = 'search';

		//IF view_prefix_name is '' it defaults to base search page
		$this->render($client['Client']['view_prefix_name'].'search');
	}

	public function result($client,$client_orig_name)
	{
		Configure::write('Model.globalSource', $client['Client']['cake_db_config']);
		$this->loadModel('Search');
		$request_data = $this->request->data;

//maybe for hidden css
		$request_data['start'] = isset($request_data['start']) && $request_data['start']>=0 ? $request_data['start']:0;
		$this->Search->set($request_data);

		if($this->Search->validates())
		{
			$results = $this->Search->getResults();
		}
		else
		{
			$error = $this->Search->validationErrors;
			$err_field = key($error);
			$err_msg = $error[$err_field][0];
			$this->Session->setFlash($err_msg, 'default', array(), $err_field);
			//$this->Session->setFlash(array($err_field,$err_msg));
			//var_dump($this->Search->invalidFields());
			return $this->redirect(array('action' => $client_orig_name));
		}

		$this->autoRender = false;
		$this->set('client_name', $client['Client']['name']);
		$this->set('srch_filter',$request_data);

		$this->set('results', $results['providers']);
		$this->set('locations', $results['locations']);
		$this->set('coor', $results['coor_array']);
		$this->set('title', 'Search Results for '.ucfirst($client['Client']['name']));
		$this->set('asset_folder', $client['Client']['asset_folder_name']);
		$this->set('client_url_name', $client_orig_name);


		$this->layout = 'result';
		$this->render($client['Client']['view_prefix_name'].'result');
	}

	// public function view()
	// {
	// 	$this->autoRender = false;

	// 	$this->layout = 'search';
	// 	$this->render('view1');
	// }

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