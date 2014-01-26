<?php

class Search extends AppModel {
	public $useTable = false;
	public $validate = array(
			'zipcode' => array(
				'numeric' => array(
					'rule' => 'numeric',
					'required' => true,
					'message' => 'Must Be a Number'
				),
				'between' => array(
             	   'rule'    => array('between', 5, 5),
             	   'message' => 'Must be 6 Digits'
             	 ),
               
            ),
			'distance'  => array(
				'rule' => 'numeric',
				'required' => true
			),
			'gender'  => array(
				'rule' => 'alphaNumeric',
				'required' => false
			),
			'boardcertified' =>array(
				'rule'       => 'alphaNumeric',
           		'required' => false
			),
			'wheelchairaccessible' =>array(
				'rule'       => 'alphaNumeric',
            	'required' => false
			),
			'insurance_id'  => array(
				'rule' => 'numeric',
				'required' => true
			),
			'language_id'  => array(
				'rule' => 'numeric',
				'required' => true
			),
			'location_id'  => array(
				'rule' => 'numeric',
				'required' => true
			),
			'specialtie_id'  => array(
				'rule' => 'numeric',
				'required' => true
			),
	);
	

	public function loadingotherclasstest()
	{  $Insurance = ClassRegistry::init('Insurance');
	//	$this->loadModel('Insurance');
		return  $Insurance->find('all');
	}

	public function customquerytest()
	{
		return $this->query("SELECT * FROM posts ",false);
	}
	public function getResults($zip,$distance,$filter_array = array())
	{
		$coor_array = $this->getCoordinatesFromZip($zip);
	
		$radius_results = $this->searchByRadius($coor_array,$distance,$filter_array);

		$location_results = $this->formatLocations($radius_results);
		$provider_results = $this->formatProviders($radius_results);

		return array('providers'=>$provider_results,'coor_array'=>$coor_array,'locations'=>$location_results);
	}

	public function formatLocations($provider_results=array())
	{
		$location_results = array();

		foreach ($provider_results as $result) {
			$location_results[$result['locations']['id']] = $result['locations'];
		}

		return $location_results;
	}

	public function formatProviders($provider_results)
	{
		$provider_array = array();
		foreach($provider_results as $provider)
		{
			$curr_prov_id 	= $provider['providers']['id'];
			$curr_loc_id 	= $provider['locations']['id'];
			$curr_lang 		= $provider['languages']['name'];
			$curr_insur 	= $provider['insurances']['name'];
			$curr_spec 		= $provider['specialties']['name'];
			$curr_prov 		= $provider['providers'];
			$curr_loc 		=  $provider['locations'];

			if(!isset($provider_array[$curr_prov_id])) //provider  doesnt exist in list
			{//SET INITIAL PROVIDER INFO INTO ARRAY WITH ID AS KEY
				$provider_array[$curr_prov_id] = $curr_prov;
				$provider_array[$curr_prov_id]['languages'] = array($curr_lang);
				$provider_array[$curr_prov_id]['insurances'] = array($curr_insur);
				$provider_array[$curr_prov_id]['specialties'] = array($curr_spec);
				$provider_array[$curr_prov_id]['locations'] = array($curr_loc);
				//add locations//addinsurances //add languages
			}
			else//provider exist in aray
			{//check if attributes exist and push if doesnt
				if(!in_array($curr_lang, $provider_array[$curr_prov_id]['languages']))
					$provider_array[$curr_prov_id]['languages'][] = $curr_lang;
				if(!in_array($curr_insur, $provider_array[$curr_prov_id]['insurances']))				
					$provider_array[$curr_prov_id]['insurances'][] = $curr_insur;
				if(!in_array($curr_spec, $provider_array[$curr_prov_id]['specialties']))				
					$provider_array[$curr_prov_id]['specialties'][] = $curr_spec;

				foreach($provider_array[$curr_prov_id]['locations'] as $providers_loc)
				{
					$is_in = false;
					if($providers_loc['id'] == $curr_loc_id)
					{
						$is_in = true;
						break;
					}
				}
				if(!$is_in)
					$provider_array[$curr_prov_id]['locations'][] = $curr_loc;
			}
		}
		
		return $provider_array;
	}

	public function getCoordinatesFromZip($zip)
	{
		//todo set errors and throws
		$api_requ = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$zip.'&sensor=false';

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

	public function searchByRadius($coor_array,$distance,$filter_array)
	{
		$this->query( "set SQL_BIG_SELECTS=1;",false);
		$this->query( "set @latitude=".$coor_array['lat'].";",false);
		$this->query( "set @longitude=".$coor_array['long'].";",false);
		$this->query( "set @radius=".$distance.";",false);
		$this->query( "set @lng_min = @longitude - @radius/abs(cos(radians(@latitude))*69.444);",false);
		$this->query(  "set @lng_max = @longitude + @radius/abs(cos(radians(@latitude))*69.444);",false);
		$this->query(  "set @lat_min = @latitude - (@radius/69.444);",false);
		$this->query(  "set @lat_max = @latitude + (@radius/69.444);",false);
	
		$insur_sel = false ? 'insurances.name ' : "'TBD' as insurances.name ";
		$sql = "SELECT providers.* , locations.*, insurances.name, languages.name, specialties.name from providers 
		JOIN providers_specialties
		ON providers.id = providers_specialties.provider_id
		JOIN specialties 
		ON specialties.id = providers_specialties.specialtie_id
		";
		if($filter_array['insurance_id'] != '0')
			$sql .=" LEFT JOIN providers_insurances 
			ON providers.id = providers_insurances.provider_id
			LEFT JOIN insurances 
			ON insurances.id = providers_insurances.insurance_id ";
		else
			$sql .= " JOIN (select 'N/A' as name ) insurances ON 1=1 ";

		$sql .= " JOIN providers_languages 
		ON providers.id = providers_languages.provider_id
		JOIN languages 
		ON languages.id = providers_languages.language_id
		JOIN providers_locations
		ON providers.id = providers_locations.provider_id
		JOIN (
			SELECT * FROM locations 
			WHERE (longitude BETWEEN @lng_min AND @lng_max) 
				AND (latitude BETWEEN @lat_min and @lat_max)) locations 
		ON providers_locations.location_id = locations.id WHERE 1 = 1";

		if(isset($filter_array['specialtie_id']) && $filter_array['specialtie_id'] != '0')
			$sql .= " AND specialties.id = ".$filter_array['specialtie_id'];
		if(isset($filter_array['insurance_id']) && $filter_array['insurance_id'] != '0')
			$sql .= " AND insurances.id = ".$filter_array['insurance_id'];
		if(isset($filter_array['language_id']) && $filter_array['language_id'] != '0')
			$sql .= " AND languages.id = ".$filter_array['language_id'];
		if(isset($filter_array['location_id']) && $filter_array['location_id'] != '0')
			$sql .= " AND locations.id = ".$filter_array['location_id'];
		if(isset($filter_array['gender']) && $filter_array['gender'] != '0')
			$sql .= " AND providers.g = '".$filter_array['gender']."'";

		// if(isset($filter_array['wheelchairaccessible']))
		// 	$sql .= " AND locations.wheelchair_accessible = 1";
		// if(isset($filter_array['location_id']) && $filter_array['location_id'] != '0')
		// 	$sql .= " AND locations.id = ".$filter_array['location_id'];

		return $this->query($sql,false);
	}

}
?>