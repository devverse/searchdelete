<?php

class Fullrecord extends AppModel {
	
	public function beforeValidate($array=array()) {
		$fullrecords = $this->data['Fullrecord'];
		foreach($fullrecords as $k=>$fr)
		{
			if(in_array($k, array('category','practicename','address','city','state','zip')))
				$this->validate[$k] = array(
					"Field '$k' Must be alphanumeric character and not blank" => array(
		                'rule'     => array('custom', '/^[a-zA-Z0-9\s_\-,\(\);]*$/i'),
		                'allowEmpty' => false,
		                'required'=>true
	            	)
            	);
			elseif (in_array($k, array('latitude','longitude','latitude_str','longitude_str')))
				$this->validate[$k] = array(
					"Field '$k' Must be numeric" => array(
		                'rule' => 'numeric',
		                'allowEmpty' => false,
		                'required'=>true
	            	)
            	);

			else
				$this->validate[$k] = array(
					"Field '$k' Must be alphanumeric character" => array(
		                'rule'     => array('custom', '/^[a-zA-Z0-9\s_\-,\(\);]*$/i'),
		                'allowEmpty' => true,
		                'required'=>true
	            	)
            	);
		}

		if(count($fullrecords) > 5)
			return true;
		else
			return false;
	} 

	public function getForUpdate($rdata='nothing')
	{
		if(!isset($rdata['Search'])||!$rdata['Search']||$rdata['Search']==null)
			return false;

		//Clean string
		$req_data = $rdata['Search'];
		$string = preg_replace("/[^A-Za-z0-9 ]/", '', $req_data);
		$array_of_terms = explode(' ', $string);
		
		$like_array['OR'] = array();
		//$like_array['sadfOR'] = array();

		foreach($array_of_terms as $term)
		{
			$like_array['OR'][]=array('Fullrecord.practicename LIKE' => "%{$term}%");
			$like_array['OR'][]=array('Fullrecord.address LIKE' => "%{$term}%"); 
		}
		
		$records = $this->find('all', array('conditions' => $like_array,'limit'=>25));

		return $records;
	}

}
?>