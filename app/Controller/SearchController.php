<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

class SearchController extends AppController {

	public $helpers = array('Html', 'Form','Session');
	public $uses = array('AppModel','Insurance','Language','Location','Provider','Specialtie','Network','Client','Providertype','Countie');
	public $components = array('RequestHandler','Email');
	public function index($client_orig_name,$action='search') {
		$this->autoRender = false;

		$network_ind=substr($client_orig_name, -1);
		if(is_numeric($network_ind)){
			$client_name = substr($client_orig_name,0,-1);
		}else{
			$client_name = $client_orig_name;
			$network_ind = false;
		}

		if(isset($client_name))
			$client_obj = $this->Client->findByName($client_name);
		else
			$action = 'error';

		switch ($action) {
			case 'search':
				$this->search($client_obj,$network_ind,$client_orig_name);
				break;
			case 'result':
				$this->result($client_obj,$network_ind,$client_orig_name);
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

	public function _getEdgeCaseNetworks($ntwk_ind=null,$networks=false,$client)
	{
		if(is_numeric($ntwk_ind) && isset($networks[$ntwk_ind-1]['Network']))
			$this_network_name = $networks[$ntwk_ind-1]['Network']['name'];
		else
			$this_network_name = $networks[0]['Network']['name'];

		if($ntwk_ind == 3 && $client['Client']['name'] == 'carewisconsin')
			$this_network_name = 'Medicaid SSI';
		if($ntwk_ind == 1 && $client['Client']['name'] == 'carewisconsin')
			$this_network_name = 'Family Care';
		if($ntwk_ind == 2 && $client['Client']['name'] == 'carewisconsin')
			$this_network_name = 'Partnership';
		if($ntwk_ind == 4 && $client['Client']['name'] == 'carewisconsin')
			$this_network_name = 'Medicare Dual Advantage';

		if($ntwk_ind == 3 && $client['Client']['name'] == 'elderserve')
			$this_network_name = 'ISNP';
		if($ntwk_ind == 1 && $client['Client']['name'] == 'elderserve')
			$this_network_name = 'MLTC';
		if($ntwk_ind == 2 && $client['Client']['name'] == 'elderserve')
			$this_network_name = 'FIDA';
		
		return $this_network_name;
	}

	public function search($client=false,$ntwk_ind=false,$client_url_name='')
	{	
		//globally define new database source
		Configure::write('Model.globalSource', $client['Client']['cake_db_config']);

		$networks = $this->Network->find('all');

		$this_network_name = $this->_getEdgeCaseNetworks($ntwk_ind,$networks,$client);

		$this->set('network_name',$this_network_name);
		//$this->AppModel->setDataSource($client['Client']['cake_db_config']);
		if($client['Client']['migrating'] == 1 )
		{
			echo 'System is being updated. Please check back in a few minutes.';
			exit;
		}
		elseif ($client['Client']['migrating'] == 0 && $client['Client']['disable'] == 1) {
			echo 'Search has been disabled by the administrator. Please contact your vendor.';
			exit;
		}

		//BaseDropDown info
		$insurances 	= $this->Insurance->find('all');
		$languages 		= $this->Language->find('all', array('order' => 'name ASC'));
		$locations 		= $this->Location->find('all');
		$providers 		= $this->Provider->find('all');
		$specialties 	= $this->Specialtie->find('all',array('order' => 'name ASC', 'group' => 'name', 'fields'=>array('id','name','parent_id')));
		$providertypes 	= $this->Providertype->find('all',array('order' => 'name ASC','fields'=>array('id','name','lob')));
		$counties 		= $this->Countie->find('all',array('order' => 'name ASC'));
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
		if(is_numeric($ntwk_ind) && $ntwk_ind != false)
			$this->set('asset_folder', $client['Client']['asset_folder_name']."_$ntwk_ind");
		else
			$this->set('asset_folder', $client['Client']['asset_folder_name']);
		$this->layout = 'search';

		//IF view_prefix_name is '' it defaults to base search page
		$this->render($client['Client']['view_prefix_name'].'search');
	}

	public function result($client,$ntwk_ind=false,$client_orig_name)
	{
		try {
			Configure::write('Model.globalSource', $client['Client']['cake_db_config']);
			$this->loadModel('Search');
			$request_data = $this->request->data;

			//start for indexing every 25 results
			$request_data['start'] = isset($request_data['start']) && $request_data['start']>=0 ? $request_data['start']:0;
			
			if(($request_data['street_address'] !=''||$request_data['city'] !='') && $request_data['state'] == 'None' && strpos($client_orig_name, 'carewisconsin') !==false)
			{
				$request_data['state'] = 'WI';
			}

			$this->Search->set($request_data);

			if($this->Search->validates())
			{
				if(isset($request_data['pdf']) || isset($request_data['email']))
				$this->Search->setLimit(25);

				$results = $this->Search->getResults();
				$resultcount = isset($this->Search->recordcount)?$this->Search->recordcount:false;
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
				'resultcount'=>$resultcount,
				'locations'=>$results['locations'],
				'coor'=>$results['coor_array'],
				'title'=>'Search Results for '.ucfirst($client['Client']['name']),
				'client_url_name'=>$client_orig_name,
				'req_data'=>$request_data
			);

			if($ntwk_ind != false)
				$this->set('asset_folder', $client['Client']['asset_folder_name']."_$ntwk_ind");
			else
				$this->set('asset_folder', $client['Client']['asset_folder_name']);

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
					$dompdf->set_paper('a4');
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
					//email using postfix
					$view->set('maplink',$this->_buildStaticMap($param));
					$view->layout = 'result';
					$view_output = $view->render($client['Client']['view_prefix_name'].'resultemail');

					$client_email = $request_data['email'];
					$Email = new CakeEmail();
					$Email->emailFormat('html');
					$Email->from(array('noreply@geosearch.commanddirect.com' => ucfirst($client['Client']['name']).' Provider Search Results'));
					//$Email->from(array('chin.geoff@gmail.com' => 'Provider Search List'));
					$Email->to($client_email);
					$Email->subject(ucfirst($client['Client']['name']).' Search Results');
					$Email->send($view_output);
					
					$resp_view = new View($this, false);
					$resp_view->set($param);
					$resp_view->set('client_email',$client_email);
					$resp_view->layout = 'result';
					echo $resp_view->render($client['Client']['view_prefix_name'].'resultemaillanding');
				
				}
				
			}
			else
			{
				$this->autoRender = false;
				$this->set($param);
				$this->layout = 'result';
				$this->render($client['Client']['view_prefix_name'].'result');
			}

		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	private function _buildStaticMap($param)
	{
		if($param['coor']['lat']){
			$center = $param['coor']['lat'].','.$param['coor']['long'];
		}else if(isset($param['results'	][1]['fullrecords']['latitude'])){
			$center = $param['results'][1]['fullrecords']['latitude'].','.$param['results'][1]['fullrecords']['longitude'];
		}else{
			return false;
		}

		$markers = '';
		$count = 0;
		foreach($param['results'] as $p)
		{
			$count++;
			$markers .= $p['fullrecords']['latitude'].','.$p['fullrecords']['longitude'].'|';
		
			if($count >25)
				break;
		}
		return "http://maps.google.com/maps/api/staticmap?center={$center}&zoom=8&size=500x300&maptype=roadmap&sensor=false&language=&markers=color:red|label:none|{$markers}";
	}

	private function _randString( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

		$str='';
		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $str;
	}
}

?>
