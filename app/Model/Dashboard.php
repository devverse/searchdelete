<?php

class Dashboard extends AppModel {
	public $useTable = false;

	public function getTables($db_name='')
	{
		$sql = "Show tables";
		$tables = $this->query($sql,false);

		$scrubed_tables = array();
		foreach ($tables as $table)
		{
			$tablename = $table['TABLE_NAMES']["Tables_in_{$db_name}_db"];
			
			$scrubed_tables[] = $tablename;
		}
		return $scrubed_tables;
	}

	public function getTableRecords($tablename,$start,$count)
	{
		$this->useTable = false;
	 	$sql = 'SELECT * FROM  ';
	 	$sql .= $tablename;
	 	$sql .= ' LIMIT '.$start.' , '.$count ;

	 	$records = $this->query($sql,false);

	 	$scrubed_records = array();
	 	foreach($records as $record)
	 	{
	 		$scrubed_records[] = $record[$model_name];
	 	}

	 	return $scrubed_records;
	}

	public function getRecordFields($table)
	{
		$fields = array();

		$sql = "Show fields from {$table}";
		$result_fields = $this->query($sql,false);

		foreach ($result_fields as $value) {
			$fields[]=$value['COLUMNS']['Field'];
		}
		
		return $fields;
	}
}
?>