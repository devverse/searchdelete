<?php

class Dashboard extends AppModel {
	public $useTable = false;

	public function getFilters($db_name='')
	{
		$sql = "Show tables";
		$filters = $this->query($sql,false);
		$scrubed_filter = array();
		foreach ($filters as $filter)
		{
			$filtername = $filter['TABLE_NAMES']["Tables_in_{$db_name}_db"];
			if($filtername != 'temptables' && strpos($filtername, '_') ===false)
			$scrubed_filter[] = $filtername;
		}
		return array('providers','locations','specialties','insurances','languages');
	}

	public function getFilterRecord($model_name,$start,$count)
	{
		$this->useTable = false;
	 	$sql = 'SELECT * FROM  ';
	 	$sql .= $model_name;
	 	$sql .= ' LIMIT '.$start.' , '.$count ;

	 	$records = $this->query($sql,false);

	 	$scrubed_records = array();
	 	foreach($records as $record)
	 	{
	 		$scrubed_records[] = $record[$model_name];
	 	}

	 	return $scrubed_records;
	}

	public function getRecordFields($records)
	{
		//instead of array_key
		if(!isset($records[0]))
			return array();
		$fields = array();
		foreach($records[0] as $key=>$v)
		{
			$fields[] = $key;
		}

		return $fields;
	}
}
?>