<?php
class SearchController extends AppController {

	public $helpers = array('Html', 'Form','Session');
	public $uses = array('AppModel','Insurance','Language','Location','Provider','Specialtie','Network','Client','Providertype','Countie');
	public $components = array('RequestHandler','Email');
	public function index($client_orig_name,$action='search') {
		$this->autoRender = false;

		$network_ind=substr($client_orig_name, -1);
		if(is_numeric($network_ind))
			$client_name = substr($client_orig_name,0,-1);
		else
			$client_name = $client_orig_name;

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

		//start for indexing every 25 results
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

		$param = array(
			'client_name'=> $client['Client']['name'],
			'srch_filter'=>$request_data,
			'results'=>$results['providers'],
			'locations'=>$results['locations'],
			'coor'=>$results['coor_array'],
			'title'=>'Search Results for '.ucfirst($client['Client']['name']),
			'asset_folder'=>$client['Client']['asset_folder_name'],
			'client_url_name'=>$client_orig_name
		);

		if(isset($request_data['pdf']) || isset($request_data['email']))
		{
			$this->autoRender = false;

			$view = new View($this, false);
			$view->set($param);

			if(isset($request_data['pdf'])){
				$static_map_link = $this->_buildStaticMap($param);
				$view->set('statimgname','sdf');
				if($static_map_link)
				{
					//curl img
					$s = curl_init();
					curl_setopt($s, CURLOPT_URL,$static_map_link);
			        curl_setopt($s, CURLOPT_VERBOSE, 0);
					curl_setopt($s, CURLOPT_HEADER, 0);
					curl_setopt($s, CURLOPT_RETURNTRANSFER, 1); 
					$imgdata = curl_exec($s); 
					curl_close($s);

					$filename = $this->_randString(10);
					$path = "../webroot/files/{$filename}.png";
					file_put_contents($path, $imgdata);
					$view->set('statimgname',$filename);
				}

				$view->layout = 'result';
				$view_output = $view->render($client['Client']['view_prefix_name'].'resultpdf');

				set_time_limit(0);
				require_once(dirname(__FILE__).'/../Vendor/dompdf/dompdf_config.inc.php');

				$dompdf = new DOMPDF();
				$dompdf->set_paper('letter');
				$dompdf->load_html($view_output);
				$dompdf->render();
				$dompdf->stream("provider.pdf");

				if($static_map_link)
					unlink($path);
				// set_time_limit(0);
				// require_once(dirname(__FILE__).'/../Vendor/html2pdf_v4.03/html2pdf.class.php');
			 //    $html2pdf = new HTML2PDF('P','A4','fr');
			 //    $html2pdf->WriteHTML($view_output);
			 //    $html2pdf->Output('proivderlist.pdf','D');
			}
			else
			{
				echo 'email test';
				// $Email = new CakeEmail();
				// $Email->from(array('chin.geoff@gmail.com' => 'My Site'));
				// $Email->to('chin.geoff@gmail.com');
				// $Email->subject('About');
				// $Email->send('My message');
			}
			
		}
		else
		{
			$this->autoRender = false;
			$this->set($param);
			$this->layout = 'result';
			$this->render($client['Client']['view_prefix_name'].'result');
		}
	}

	private function _buildStaticMap($param)
	{
		if($param['coor']['lat']){
			$center = $param['coor']['lat'].','.$param['coor']['long'];
		}else if(isset($param['results'	][0]['fullrecords']['latitude'])){
			$center = $param['results'][0]['fullrecords']['latitude'].','.$param['results'][0]['fullrecords']['longitude'];
		}else{
			return false;
		}

		$markers = '';
		foreach($param['results'] as $p)
		{
			$markers .= $p['fullrecords']['latitude'].','.$p['fullrecords']['longitude'].'|';
		}
		return "http://maps.google.com/maps/api/staticmap?center={$center}&zoom=8&size=500x300&maptype=roadmap&sensor=false&language=&markers=color:red|label:none|{$markers}";
	}

	private function _randString( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $str;
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