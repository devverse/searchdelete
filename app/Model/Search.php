<?php

class Search extends AppModel {
	public $useTable = false;
	public $limit = false;
	public $validate = array(
			'street_address'=>array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => true,
            	)
			),
			'city'=>array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => true,
            	)
            ),
			'state'=>array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => false,
            	)
            ),
            'zipcode' => array(
            	'Please fill in an address.' => array(
	                'rule'=>array('locationOrCountieOrPracticeName'),
            	),
				'Invalid ZIP code.' => array(
					'rule' => 'numeric',
					'allowEmpty' => true,
				),
				'ZIP code must be 6 digits.' => array(
             	   'rule'    => array('between', 5, 5)
             	 ),
               
            ),
            'distance'  => array(
            	'Select a distances or enter one.' => array(
	               'rule'=>array('distOrDistcust'),
            	),
				'Must be a number whole number.' => array(
					'rule' => 'numeric',
					'allowEmpty' => true
				),
			),
			'distance_c'  => array(
				'Select a distances or enter one.' => array(
	               'rule'=>array('distOrDistcust'),
            	),
				'Must be a number whole number.' => array(
					'rule' => 'numeric',
					'allowEmpty' => true
				),
				'Must be less than 100 miles.' => array(
             	   'rule'    => array('between', 1, 2),
             	   'allowEmpty' => true
             	),
            ),

			// 'firstname'=>array(
			// 	'AlphaNumeric Characters only' => array(
	  //               'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	  //               'allowEmpty' => true
   //          )),
			// 'lastname'=>array(
			// 	'AlphaNumeric Characters only' => array(
	  //               'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	  //               'allowEmpty' => true,
   //          )),
			// 'practicename'=>array(
			// 	'alphaNumeric' => array(
	  //               'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	  //               'allowEmpty' => true,
   //          )),


			'gender'  => array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => false,
            	)
            ),
			'handicapaccess' =>array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => false,
            	)
            ),
			'insurance_name'  => array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => false,
            	)
            ),
			// 'language_name'  => array(
			// 	'Must be Alpha Numeric Character' => array(
	  //               'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	  //               'allowEmpty' => false,
   //          	)
   //          ),
			'acceptnew'  => array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => false,
            	)
            ),
			'acceptmedicare'  => array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => false,
            	)
            ),
			'acceptmedicaid'  => array(
				'Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => false,
            	)
            ),
            'start'  => array(
				'Must be a number whole number.' => array(
					'rule' => 'numeric',
					'allowEmpty' => true
				),
            )
	);

	public function distOrDistcust($field = array(), $other_field = null)
	{
		$d = $this->data['Search'];
		if(empty($d['distance']) && empty($d['distance_c']))
			return false;
		else
			return true;
	}

	public function locationOrCountieOrPracticeName($field = array())
	{
		$d = $this->data['Search'];
		if(strtolower($d['countie_name']) == 'none' && strtolower($d['state']) == 'none'  && empty($d['street_address']) && empty($d['city'])  && empty($d['zipcode']) && empty($d['practicename']) && empty($d['firstname']) && $d['providertype_name']=='none')
			return false;
		else
			return true;
	}

	public function setLimit($limit = 25)
	{
		$this->limit = $limit;
	}
	
	public function getResults()
	{
		$limit = 25;

		if(strtolower($this->data['Search']['countie_name']) == 'none')
			$coor_array = $this->getCoordinatesFromAddress();
		else
			$coor_array = false;
	
		$radius_results = $this->searchByRadius($limit, $coor_array);

		$location_results = $this->formatLocations($radius_results);

		$provider_results = $this->formatProviders($radius_results);

		if($coor_array == false || $coor_array['lat']==null)
			$coor_array = $this->getAverageCoordinates($radius_results);

		return array('providers'=>$provider_results,'coor_array'=>$coor_array,'locations'=>$location_results);
	}

	public function getAverageCoordinates($result_array)
	{
		$average_array = array();
		$count = 0;
		$index = 0;
		foreach($result_array as $k=>$v)
		{
			$v = $v['fullrecords'];
			if(isset($average_array[$v['state']][$v['city']])){
				$average_array[$v['state']][$v['city']]['count']++;
			}else{
			 	$average_array[$v['state']]=array($v['city']=>array('count'=>1,'index'=>$k));
			}	

			if($count < $average_array[$v['state']][$v['city']]['count'])
			{
				$count = $average_array[$v['state']][$v['city']]['count'];
				$index = $average_array[$v['state']][$v['city']]['index'];
			}
		}

		return array('lat'=>$result_array[$index]['fullrecords']['latitude'],'long'=>$result_array[$index]['fullrecords']['longitude']);
	}

	public function getCoordinatesFromAddress()
	{
		$d = $this->data['Search'];
		$address  = ($d['street_address'] !='')? str_replace(' ', '+', $d['street_address']).'+': '';
		$address .= ($d['city']!='')? str_replace(' ', '+', $d['city']).'+': '';
		$address .= (strtolower($d['state'])!='none')? str_replace(' ', '+', $d['state']).'+': '';
		$address .= ($d['zipcode']!='')? str_replace(' ', '+', $d['zipcode']): '';

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

	public function formatLocations($provider_results=array())
	{
		$location_results = array();

		foreach ($provider_results as $result) {
			$location_results[$result['fullrecords']['id']] = $result['fullrecords'];
		}

		return $location_results;
	}

	public function formatProviders($provider_results)
	{
		$provider_array = $provider_results;
		return $provider_array;
	}

	public function searchByRadius($limit = 25, $coor_array=false)
	{
		$d = $this->data['Search'];

		//SQL_CALC_FOUND_ROWS
		if($d['practicename'] != ''){
			$sql = "SELECT SQL_CALC_FOUND_ROWS company, county, lob, provId,category,specialty,practicename,firstname,middlename,lastname,suffix,degree,address,suite,city,state,zip,zip4,county,servicearea,phone,fax,gender,handicap,acceptingnew,acceptsmedicaid,hospaffiliations,languages,officehours,customfield1desc,customfield1ind,customfield2desc,customfield2ind,customfield3desc,customfield3ind,latitude_str,longitude_str,latitude,longitude,id,specialexperince,publictransavailable,certifications FROM fullrecords WHERE ";
		}else{
			$sql = "SELECT SQL_CALC_FOUND_ROWS company, county, lob, provId,category,specialty,practicename,firstname,middlename,lastname,suffix,degree,address,suite,city,state,zip,zip4,county,servicearea,phone,fax,gender,handicap,acceptingnew,acceptsmedicaid,hospaffiliations,languages,officehours,customfield1desc,customfield1ind,customfield2desc,customfield2ind,customfield3desc,customfield3ind,latitude_str,longitude_str,latitude,longitude,id,specialexperince,publictransavailable,certifications FROM fullrecords WHERE ";
		}

		$distance = ($d['distance_c'] != '')? $d['distance_c'] : $d['distance'];

		if ($distance == 1) {
			$distance = 2.5;
		}

		$practicename = $d['practicename'];
		$namesearch = false;

		if (isset($d['firstname']) || isset($d['lastname'])) {
			$firstname = $d['firstname'];
			$lastname = $d['lastname'];
			$namesearch = true;
		}

		if($coor_array && $coor_array['lat'] && $coor_array['long']) {
			$this->query( "set @latitude=".$coor_array['lat'].";",false);
			$this->query( "set @longitude=".$coor_array['long'].";",false);
			$this->query( "set @radius=".$distance.";",false);
			$this->query( "set @lng_min = @longitude - @radius/abs(cos(radians(@latitude))*69.444);",false);
			$this->query(  "set @lng_max = @longitude + @radius/abs(cos(radians(@latitude))*69.444);",false);
			$this->query(  "set @lat_min = @latitude - (@radius/69.444);",false);
			$this->query(  "set @lat_max = @latitude + (@radius/69.444);",false);
			$this->query( "set SQL_BIG_SELECTS=1;",false);

			$sql .= " (longitude BETWEEN @lng_min and @lng_max) AND (latitude BETWEEN @lat_min and @lat_max) ";

			if ($d['practicename'] != '' && $namesearch == false) {
				$sql .= " AND (practicename LIKE '%{$practicename}%') ";
			}

			if ($namesearch == true) {
				$sql .= " AND firstname LIKE '%{$firstname}%' AND lastname LIKE '%{$lastname}%'";
			}

			// Old Search Method before $namesearch above
			// if ($d['practicename'] != '') {
			// 	$sql .= " AND (practicename REGEXP '{$practicename}' or firstname REGEXP '{$practicename}' or lastname REGEXP '{$practicename}') ";
			// }

		} elseif ($d['practicename'] != '') {
			$sql .= " (practicename LIKE '%{$practicename}%') ";
		} 

		elseif ($d['firstname'] != '' && $d['lastname'] != '') {

			$sql .= "(firstname LIKE '%{$firstname}%' AND lastname LIKE '%{$lastname}%') ";
		} 

		elseif ($d['countie_name']!='none'){
	//		$sql .= " (county collate latin1_swedish_ci = '{$d['countie_name']}' OR ";
	//		$sql .= " servicearea like '%{$d['countie_name']}%') ";
			$sql .= " county like '%{$d['countie_name']}%' ";
		} elseif ($d['practicename'] == '' && $d['providertype_name']!='none') {
			$sql .= ' 1 =1 ';
		} else{
			throw new Exception("Search type was not properly formated", 1);
		}

		$sql .= $this->_buildAndSql();

		// if($this->limit)
		// {
		// 	$limit = $this->limit;
		// 	$d['start']=1;
		// }

		if($d['practicename'] != '')
		{
			// $sql .= ' Group By fullrecords.practicename, fullrecords.category,fullrecords.specialty LIMIT '.$d['start'].' , '.$limit ;
			
			if(isset($d['search_user']) && strtolower($d['search_user']) == 'partnerhealthplan' && empty($d['street_address'])) {
				$sql.= ' ORDER BY fullrecords.practicename ASC,fullrecords.lastname ASC LIMIT '.$d['start'].' , '.$limit;
			}
		} else {

			if(isset($d['search_user']) && strtolower($d['search_user']) == 'partnerhealthplan' && $coor_array['lat'] && $coor_array['long'] ) {
				$sql.= ' ORDER BY (POW((longitude-' . $coor_array['long'] . '),2) + POW((latitude-' . $coor_array['lat'] .'),2))' . ' LIMIT '.$d['start'].' , '.$limit;
			} else {
				$sql.= ' LIMIT '.$d['start'].' , '.$limit;
			}

			// if(isset($d['search_user']) && strtolower($d['search_user']) == 'partnerhealthplan' && empty($d['street_address'])) {
			// 	$sql.= ' ORDER BY fullrecords.practicename ASC,fullrecords.lastname ASC';
			// }

			// $sql .= ' Group By fullrecords.address, fullrecords.practicename LIMIT '.$d['start'].' , '.$limit;
		}

		if (isset($d['debug'])) {
			if ($d['debug'] == 'true') {
				var_dump($sql); exit;
			}
		}
		
		$records = $this->query($sql,false);
		$recordcount = $this->query('SELECT FOUND_ROWS()');
		$this->recordcount = $recordcount[0][0]["FOUND_ROWS()"];


		return $records;
	}

	function _buildAndSql()
	{
		$d = $this->data['Search'];
		$sql = '';

		if(isset($d['zipcode'])) {
			$zip = $d['zipcode'];
			//$sql .= " AND zip LIKE '%{$zip}%'";
		}

		if(isset($d['gender']) && strtolower($d['gender'])!='none')
			$sql .= " AND gender = '{$d['gender']}'";
		
		if(isset($d['countie_name']) && strtolower($d['countie_name'])!='none')
			$sql .= " AND county collate latin1_swedish_ci ='{$d['countie_name']}'";

		if(isset($d['network_name']) && strtolower($d['network_name'])!='none')
			$sql .= " AND lob collate latin1_swedish_ci ='{$d['network_name']}'";

		if(isset($d['specialtie_name']) && strtolower($d['specialtie_name'])!='none')
			$sql .= " AND specialty ='{$d['specialtie_name']}'";

		if(isset($d['providertype_name']) && strtolower($d['providertype_name'])!='none')
			$sql .= " AND category  ='{$d['providertype_name']}'";

		if(isset($d['language_name']) && strtolower($d['language_name'])!='none')
			$sql .= " AND languages LIKE'%{$d['language_name']}%'";

		if(isset($d['acceptnew']) && strtolower($d['acceptnew'])!='none')
			$sql .= " AND acceptingnew collate latin1_swedish_ci ='{$d['acceptnew']}'";

		if(isset($d['acceptmedicare']) && strtolower($d['acceptmedicare'])!='none')
			$sql .= " AND acceptsmedicare collate latin1_swedish_ci ='{$d['acceptmedicare']}'";

		if(isset($d['acceptmedicaid']) && strtolower($d['acceptmedicaid'])!='none')
			$sql .= " AND acceptsmedicaid collate latin1_swedish_ci ='{$d['acceptmedicaid']}'";

		if(isset($d['handicapaccess']) && strtolower($d['handicapaccess'])!='none')
			$sql .= " AND handicap collate latin1_swedish_ci ='{$d['handicapaccess']}'";

		if(isset($d['insurance_name']) && strtolower($d['insurance_name'])!='none')
			$sql .= " AND insurance collate latin1_swedish_ci ='{$d['insurance_name']}'";

		if(isset($d['firstname']) && strtolower($d['firstname'])!='none'&& strtolower($d['firstname'])!='')
			$sql .= " AND firstname LIKE '%{$d['firstname']}%'";

		if(isset($d['lastname']) && strtolower($d['lastname'])!='none' && strtolower($d['lastname'])!='')
			$sql .= " AND lastname LIKE '%{$d['lastname']}%'";


		return $sql;
	}
}
?>
