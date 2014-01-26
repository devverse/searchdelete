<?php

class Temptable extends AppModel {
	 public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    ); 

	 public function populateDB()
	 {
	 	$datas = $this->find('all');

	 	$provider_m = ClassRegistry::init('Provider'); 
	 	$location_m = ClassRegistry::init('Location'); 
	 	$specialtie_m = ClassRegistry::init('Specialtie'); 

		foreach($datas as $key=>$data)
		{
			$conditions = array("name" => $data['Temptable']['FIRST_NAME'].' '.$data['Temptable']['LAST_NAME'], "title" =>$data['Temptable']['DEGREE'],"phone"=>$data['Temptable']['PHONE']);
			$providers = $provider_m->find('first',array('conditions'=>$conditions));
			if(!isset($providers['Provider']['id']))
			{
				$provider_m->create();
				$provider_m->save(array(
					'name'=>$data['Temptable']['FIRST_NAME'].' '.$data['Temptable']['LAST_NAME'],
					'fname'=>$data['Temptable']['FIRST_NAME'],
					'lname'=>$data['Temptable']['LAST_NAME'],
					'mname'=>'',
					'g'=>'m',
					'title'=>$data['Temptable']['DEGREE'],
					'biography'=>'',
					'phone'=>$data['Temptable']['PHONE'],
					'website'=>'',
					'image_path'=>'',
					'provider_type'=>'1',
					'board_certified'=>'0'
					));
				$doc_id = $provider_m->id;
			}
			else
			{
				$doc_id = $providers['Provider']['id'];
			}
			$provider_m->clear();


			$conditions = array("name" => $data['Temptable']['OFFICE_NAME'], "address1" => $data['Temptable']['ADDRESS']);
			$locations = $location_m->find('first',array('conditions'=>$conditions));
			if(!isset($locations['Location']['id']))
			{
				$location_m->create();
				$location_m->save(array(
				'name'=>$data['Temptable']['OFFICE_NAME'],
				'address1'=>$data['Temptable']['ADDRESS'],
				'address2'=>'',
				'address3'=>'',
				'address4'=>'',
				'city'=>$data['Temptable']['CITY'],
				'state'=>$data['Temptable']['STATE'],
				'country'=>'AMERICA',
				'zipcode'=>$data['Temptable']['ZIP'],
				'phone'=>$data['Temptable']['PHONE'],
				'ephone'=>'',
				'website'=>'',
				'wheelchair_accessible'=>'0',
				'longitude'=>'0',
				'latitude'=>'0'
				));
				$loc_id = $location_m->id;
			}
			else
			{
				$loc_id = $locations['Location']['id'];
			}
			$location_m->clear();
 			
 			$conditions = array("name" => $data['Temptable']['SPECIALTY']);
			$specialtie = $specialtie_m->find('first',array('conditions'=>$conditions));
			
			if(!isset($specialtie['Specialtie']['id']))
			{
 				$specialtie_m->create();
 				$specialtie_m->save(array('name'=>$data['Temptable']['SPECIALTY']));
 				$spe_id = $specialtie_m->id;
 			}
 			else
 			{
 				$spe_id = $specialtie['Specialtie']['id'];
 			}
 			$specialtie_m->clear();

 			$lan_id = 2;

 			$this->cacheQueries = false; 
 			//save providers_languages
			$join = $this->query("Select * FROM providers_languages where language_id = '{$lan_id}' AND provider_id = '{$doc_id}'",false);
			if(!isset($join[0]))
 				$this->query("INSERT INTO providers_languages (provider_id,language_id) VALUES ({$doc_id},{$lan_id})",false);

 			//save providers_locations
 			$join = $this->query("Select * FROM providers_locations where location_id = '{$loc_id}' AND provider_id = '{$doc_id}'",false);
			if(!isset($join[0]))
 			$this->query("INSERT INTO providers_locations (provider_id,location_id) VALUES ({$doc_id},{$loc_id})",false);

 			//save_providers_specialties
 			$join = $this->query("Select * FROM providers_specialties where specialtie_id = '{$spe_id}' AND provider_id = '{$doc_id}'",false);
			if(!isset($join[0]))
 			$this->query("INSERT INTO providers_specialties (provider_id,specialtie_id) VALUES ({$doc_id},{$spe_id})",false);

		}
		echo 'Populate DB Done';
	}

	public function geocodeCurrentLocations()
	{
		ini_set('max_execution_time', 0);
		$location_m = ClassRegistry::init('Location');
		$locations = $location_m->find('all');
		
		foreach($locations as $key=>$loc)
		{
			$location_m->id = $loc['Location']['id'];

			$addressBuild = str_replace (" ","+", urlencode(str_replace(array(",","."), "",$loc['Location']['address1']).' '.$loc['Location']['city'].' '.$loc['Location']['state'].' '.$loc['Location']['zipcode']));
			$longlat = $this->getCoordinatesFromAddress($addressBuild);

			$location_m->save(array(
					'longitude'=>$longlat['long'],
					'latitude'=>$longlat['lat']
					));
			$location_m->clear();

			sleep(1);
			
		}
		echo 'Geocode Done';
	}

	public function getCoordinatesFromAddress($address)
	{
		//todo set errors and throws
		$api_requ = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false';

		$s = curl_init();
        curl_setopt($s,CURLOPT_URL,$api_requ);
        curl_setopt($s, CURLOPT_VERBOSE, 0);
		curl_setopt($s, CURLOPT_HEADER, 0);
		curl_setopt($s, CURLOPT_RETURNTRANSFER, 1); 
		$sData = curl_exec($s); 
		curl_close($s);
		$result_array = json_decode($sData,true);
		$long = $result_array['results'][0]['geometry']['location']['lng'];
		$lat = $result_array['results'][0]['geometry']['location']['lat'];
		return array('lat'=>$lat,'long'=>$long);	
	}
	 
}
?>