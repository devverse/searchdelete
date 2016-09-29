<?php

class Migration extends AppModel {
	public $useTable = false;
	private $file_handle = false;


	public function __construct()
	{
		parent::__construct();
		$this->Client = ClassRegistry::init('Client');
	}

	public function setClient($client_id){
		//clears client
		$this->Client->clear();
		//sets migration modes member client info
		$this->client_info = $this->Client->read(null,$client_id);
		//sets migration status
		$this->migration_in_progress = ($this->client_info['Client']['migrating'] == 1)?true:false;
	}

	public function import($file_path)
	{
		// if($this->migration_in_progress)
		// 	return array('status'=>false,'response'=>'Migration in progress for this Client!');

		$this->_setMigrationProcess(true);

		$this->_unzipFile($file_path);
		$resp = $this->_checkData();

		if($resp['status'])
			$resp = $this->_importToMysql();

		if($this->file_handle)
			fclose($this->file_handle);

		$this->_setMigrationProcess(false);
		$this->Client->clear();

		return $this->formResp(true,"Database records created.");
	}

	public function truncateTable()
	{
		$this->query("TRUNCATE TABLE fullrecords", false);
	}

	private function _setMigrationProcess($migrating)
	{
		if($migrating){
			$this->Client->set('migrating',1);
			$this->migration_in_progress = true;
		}
		else
		{	
			$this->Client->set('migrating',0);
			$this->migration_in_progress = false;
		}
		$this->Client->save();
	}

	private function _unzipFile($file_path=''){
		$zip = new ZipArchive;

		$res = $zip->open($file_path);
		if ($res === TRUE) {
		  // extract it to the path we determined above
		  $zip->extractTo('../webroot/files/'.$this->client_info['Client']['name'],'providers.txt');
		  $zip->close();
		  return $this->formResp(true,'Zip extracted');
		} else {
		   return $this->formResp(false,'Zip not extracted');
		}
	}

	private function _checkData(){
		$file = "../webroot/files/".$this->client_info['Client']['name']."/providers.txt";
		$handle = fopen($file, "r");
		if ($handle===false)
			return $this->formResp(false,"Cant open file $file");
		
		$this->file_handle = $handle;
		$tdv_fields = fgets($this->file_handle);
		if($tdv_fields !== false)
		{	
			$resp = $this->_validateFields($tdv_fields);
		}
		else
		{
			$resp = $this->formResp(false,'Fields Could not be retrieved from first line of file');
		}

	    return $resp;
	}

	private function _validateFields($tab_del_v_fields)
	{
		$field_array = $this->_convertToArray($tab_del_v_fields);

		$this->array_needed = array('company','lob','provid','category','specialty','practicename','firstname','middlename','lastname','suffix','degree','address','suite','city','state','zip','zip4','county','servicearea','phone','fax','gender','handicap','acceptingnew','acceptsmedicare','acceptsmedicaid','hospaffiliations','languages','officehours','customfield1desc','customfield1ind','customfield2desc','customfield2ind','customfield3desc','customfield3ind','latitude','longitude', 'tty', 'specialexperience', 'adacapabilities', 'certifications', 'culturalcompetancy', 'publictransavailable');
		$ret_arr = array_diff($field_array,$this->array_needed);

		if(count($ret_arr) !=0)
			$resp = $this->formResp(false,'These fields are missing or incorrectly spelled: '.var_export($ret_arr,true));
		else
			$resp = $this->formResp(true,'Field names correct.');

		return $resp;
	}

	/**
	 * return formated response with code status and response string or throw error
	 */
	private function formResp($status = false,$resp = 'No Response Form',$cdoe=0,$retType=1){
		switch ($retType) {
			case 1:
					return array('status'=>$status,'response'=>$resp);
				break;
			
			default:
					return array('status'=>$status,'response'=>$resp);
				break;
		}
	}

	/**
	 * Process to import tsv data to mysql related records
	 */
	private function _importToMysql(){
		set_time_limit(0);
		$this->cacheQueries = false; 

		//set database to use
		$dataSource = ConnectionManager::getDataSource($this->client_info['Client']['cake_db_config']);
		$database = $dataSource->config['database'];
		//need to change the database config to use clients database
		Configure::write('Model.globalSource', $this->client_info['Client']['cake_db_config']);
		//imports tempalted mysql related tables

		$this->_importTemplateSchema($database);

		if(isset($_SERVER['SERVER_NAME'])&& strpos($_SERVER['SERVER_NAME'],'localhost') !==false)
			$path = '/var/www/commandgeosearch/app/webroot/files/'.$this->client_info['Client']['name'].'/providers.txt';
		else
			$path = '/var/www/html/providersearch.geoffreychin.com/app/webroot/files/'.$this->client_info['Client']['name'].'/providers.txt';

		$this->_importDataFromFile($database,$path);
		$this->_cleanRecords($database);
		$this->_insertSecTbleData();
		//change back to default databases
	    Configure::write('Model.globalSource', 'default');
	}

	private function _insertSecTbleData()
	{
		$this->cacheQueries = false; 
		$this->query("INSERT INTO `networks`( `name`) select distinct lob from fullrecords",false);

		$this->query("INSERT INTO `providertypes`( `name`,`lob`) select distinct category,lob from fullrecords	",false);
		$this->query("INSERT INTO `specialties`( `name`,`parent_id`) select distinct fullrecords.specialty , providertypes.id from fullrecords left join providertypes on providertypes.name = fullrecords.category",false);

		$this->query("INSERT INTO `counties`( `name`) select distinct servicearea from fullrecords",false);
	}

	private function _cleanRecords($database=false)
	{
		//Removes double quotes dont know why they are there
		$qry = 'UPDATE fullrecords set latitude_str=REPLACE(latitude_str,\'\"\',\'\'), longitude_str=REPLACE(longitude_str,\'\"\',\'\')';
		foreach($this->array_needed as $flds)
		{
			if(in_array($flds, array('id','longitude','latitude')))
				continue;
			$qry .= ', '.$flds.'=REPLACE('.$flds.',\'\"\',\'\')';
		}

		$cmd_clean	= $this->_buildMysqlCommandWrapper($qry,$database);
		$stat 		= $this->_executeCommandLine($cmd_clean,false);

		//Converts string coords to float coords
		$qry 		= "UPDATE fullrecords SET latitude = CAST(latitude_str AS DECIMAL(10,6)), longitude = CAST(longitude_str AS DECIMAL(10,6))";
		$cmd_cast	= $this->_buildMysqlCommandWrapper($qry,$database);
		$stat 		= $this->_executeCommandLine($cmd_cast,false);

	}
		/**
	 * Function to clone template blank mysql schema to clients new db
	 */
	private function _importTemplateSchema($database = false)
	{
		if(!$database)
			return $this->formResp(false,'Bad Database during _importTemplateMysql');
		$stat=array();

		//Drop Old DB
		$drp_db_cmd = 'Drop DATABASE '.$database;
		$drp_db_cmd = $this->_buildMysqlCommandWrapper($drp_db_cmd,'');
		$stat[] 	= $this->_executeCommandLine($drp_db_cmd);

		//Creat new client db
		$crt_db_cmd = 'Create DATABASE '.$database;
		$crt_db_cmd = $this->_buildMysqlCommandWrapper($crt_db_cmd,'');
		$stat[] 	= $this->_executeCommandLine($crt_db_cmd);

		//Import Base Schema for client db
		$tmp_db_cmd = $database.' < ../../template_db.sql';
		$tmp_db_cmd = $this->_buildMysqlCommandWrapper($tmp_db_cmd,'',false);
		$stat[] 	= $this->_executeCommandLine($tmp_db_cmd,false);

		//Reset indexes back to0
		$alt_db_cmd = 'ALTER TABLE fullrecords AUTO_INCREMENT=1';
		$alt_db_cmd = $this->_buildMysqlCommandWrapper($alt_db_cmd,$database);
		$stat[] 	= $this->_executeCommandLine($alt_db_cmd);

		foreach($stat as $v)
		{
			if(!$v['status'])
				return $v;
		}

		return $this->formResp(true,'Import Template DB completed');
	}

	private function _importDataFromFile($database=false,$file_path='')
	{
		if(!$database || $file_path == '')
			return $this->formResp(false,'Bad Database or filepath during _importDataFromFile');

		$imp_db_cmd = "LOAD DATA LOCAL INFILE '{$file_path}' INTO TABLE fullrecords IGNORE 2 LINES";
		$imp_db_cmd = $this->_buildMysqlCommandWrapper($imp_db_cmd,$database);
		$stat 		= $this->_executeCommandLine($imp_db_cmd,false);

		return $stat;
	}

	private function _buildMysqlCommandWrapper($mysql_cmd=false,$db='',$has_e = true)
	{
		if(!$mysql_cmd)
			return false;
		$usr = 'root';
		if(isset($_SERVER['SERVER_NAME'])&& strpos($_SERVER['SERVER_NAME'],'localhost') !==false)
			$pswd = 'aspire5610z';
		else
			$pswd = 'dBCommand2014$';

		$cmd = "mysql -u{$usr} -p{$pswd} --local-infile --verbose {$db} -e ";
		$cmd.= "\" {$mysql_cmd} \"";

		if(!$has_e)
			$cmd = "mysql -u{$usr} -p{$pswd} --local-infile --verbose {$mysql_cmd}";
		
		return $cmd;
	}

	private function _executeCommandLine($cmd_string='',$exsc=true)
	{
		if($exsc)
			$cmd_string = escapeshellcmd($cmd_string);

		$ret = exec($cmd_string,$output, $exit_code);

		$resp = 'Return: '.var_export($ret,true)." | Output: ".var_export($output,true);
		$stat = ($exit_code==0)?true:false;


		var_dump($resp);
		var_dump($stat); exit;
		return $this->formResp($stat,$resp);
	}


	private function _importToMysql2(){
		set_time_limit(0);
		//imports tempalted mysql related tables
		$this->_importTemplateMysql();

		//need to change the database config to use clients database
		Configure::write('Model.globalSource', $this->client_info['Client']['cake_db_config']);

		//foreach line place correct data in tables and relate to correct tables
		//check for repeat data
		$i = 1;
$t1 = microtime();
		while (($buffer = fgets($this->file_handle)) !== false) {
			try {
				$line_array = $this->_convertToArray($buffer,false);
	        	$line_array = array_combine($this->array_needed, $line_array);
var_dump($line_array);
	        	//$this->_insertRecord($line_array);
			} catch (Exception $e) {
				echo 'Problem happend on record # '. $i.'.'.' Error:'.$e->getMessage();
			}
        	
		$i++;
 if($i == 2)
 	break;
	    }
$t2 = microtime();
var_dump($t2-$t1.' - time to migrate');
var_dump($i);
	    if (!feof($this->file_handle)) {
	        $this->formResp(false,'End of file problem.');
	    }

	    //change back to default databases
	    Configure::write('Model.globalSource', 'default');	
	}

	private function _insertRecord($record_array)
	{
		//var_dump($record_array);

		// $arr_ins_ids = $this->_checkAndInsertSingleNameRecords('Insurance','');
		// $arr_cou_ids = $this->_checkAndInsertSingleNameRecords('Countie',$record_array['servicearea']);
		// $arr_lan_ids = $this->_checkAndInsertSingleNameRecords('Language',$record_array['languages']);
		// $arr_spe_ids = $this->_checkAndInsertSingleNameRecords('Specialtie',$record_array['specialty']);
		// $arr_net_ids = $this->_checkAndInsertSingleNameRecords('Network',$record_array['lob']);

		//Creates Primary Record s
		//$loc_ids 	 = $this->_insertLocations($record_array);
		//$prov_ids = $this->_insertProviders($record_array);
		$fullRecordId = $this->_insertFullRecord($record_array);

		// $arr_form_ids = array('insurances'=>$arr_ins_ids,'counties'=>$arr_cou_ids,'languages'=>$arr_lan_ids,'specialties'=>$arr_spe_ids,'locations'=>$loc_ids,'networks'=>$arr_net_ids);

		//Creates Joins
		//$this->_insertProviderJoins($prov_ids,$arr_form_ids);
		//$this->_insertLocationJoins($loc_ids[0],$arr_form_ids);
		
	}

	private function _insertLocationJoins($loc_id,$array_of_ids)
	{
		if(!$loc_id)
			return false;
		unset($array_of_ids['locations']);

		$this->cacheQueries = false; 

		foreach($array_of_ids as $k=>$id_array)
		{
			switch ($k) {
				case 'insurances':
					$tbl = 'locations_insurances';
					$fld = 'insurance_id';
					break;
				case 'counties':
					$tbl = 'locations_counties';
					$fld = 'countie_id';
					break;
				// case 'languages':
				// 	$tbl = 'locations_languages';
				// 	$fld = 'language_id';
				// 	break;
				case 'specialties':
					$tbl = 'locationsd_specialties';
					$fld = 'specialtie_id';
					break;
				case 'networks':
					$tbl = 'locations_networks';
					$fld = 'network_id';
					break;
				
				default:
					unset($tbl);
					unset($fld);
					break 2;
			}

			if(count($id_array) > 0)
			{	
				foreach($id_array as $id)
				{
					//save locations_*
					$join = $this->query("Select * FROM {$tbl} where {$fld} = '{$id}' AND location_id = '{$loc_id}'",false);
					if(!isset($join[0]))
		 				$this->query("INSERT INTO {$tbl} (location_id,{$fld}) VALUES ({$loc_id},{$id})",false);
				}
			}
		}
	}

	private function _insertProviderJoins($prv_id,$array_of_ids)
	{
		if(!$prv_id)
			return false;

		$this->cacheQueries = false; 

		foreach($array_of_ids as $k=>$id_array)
		{
			switch ($k) {
				case 'insurances':
					$tbl = 'providers_insurances';
					$fld = 'insurance_id';
					break;
				case 'counties':
					$tbl = 'providers_counties';
					$fld = 'countie_id';
					break;
				// case 'languages':
				// 	$tbl = 'providers_languages';
				// 	$fld = 'language_id';
				// 	break;
				case 'specialties':
					$tbl = 'providers_specialties';
					$fld = 'specialtie_id';
					break;
				case 'locations':
					$tbl = 'providers_locations';
					$fld = 'location_id';
					break;
				case 'networks':
					$tbl = 'providers_networks';
					$fld = 'network_id';
					break;
				
				default:
					break 2;
			}
			if(count($id_array) > 0)
			{
				foreach($id_array as $id)
				{
					//save providers_*
					$join = $this->query("Select * FROM {$tbl} where {$fld} = '{$id}' AND provider_id = '{$prv_id}'",false);
					if(!isset($join[0]))
		 				$this->query("INSERT INTO {$tbl} (provider_id,{$fld}) VALUES ({$prv_id},{$id})",false);
				}
			}
		}	
	}

	private function _insertFullRecord($record_array)
	{

		$fullrecord_m = ClassRegistry::init('Fullrecord');
		foreach($record_array as $k=>$v)
		{
			$record_array[$k] = (str_replace(' ','',$v)=='')?'':$v;

			if(in_array($k, array('handicap','acceptingnew','acceptsmedicare','acceptsmedicaid')))
				$record_array[$k] = (strtolower(str_replace(' ','',$v))=='y')?'y':'n';
			
			if($k=='latitude'||$k=='longitude')
				$record_array[$k] = floatval($v);

		}

		$fullrecord_m->create();
		$fullrecord_m->save($record_array);
		$fr_id=$fullrecord_m->id;
		$fullrecord_m->clear();
		return $fr_id;
	}

	private function _insertProviders($record_array)
	{
		if($record_array['firstname'] == '')
			return false;

		// var_dump($array_of_ids);
		//insertproviders
		$provider_m = ClassRegistry::init('Provider');
		$find_save_array = array(
					'name'=>$record_array['firstname'].' '.$record_array['lastname'],
					'fname'=>$record_array['firstname'],
					'mname'=>'',
					'lname'=>$record_array['lastname'],
					'suffix'=>$record_array['suffix'],
					'g'=>$record_array['gender'],
					'title'=>$record_array['degree'],
					'biography'=>'',
					'phone'=>$record_array['phone'],
					'website'=>'',
					'category'=>$record_array['category'],
					'board_certified'=>0,
					'prov_id'=>$record_array['provid'],
					'acpt_new'=>($record_array['acceptingnew']=='')?0:1,
					'acpt_medicare'=>($record_array['acceptsmedicare']=='')?0:1,
					'acpt_medicaid'=>($record_array['acceptsmedicaid']=='')?0:1,
					'hours'=>$record_array['officehours'],
					'hosp_affl'=>$record_array['hospaffiliations']
					);
		$data = $provider_m->find('first',array('conditions'=>array('AND'=>array('name'=>$record_array['firstname'].' '.$record_array['lastname'],'title'=>$record_array['degree']))));

		if(!isset($data['Provider']['id']))
		{
			$provider_m->create();
			$provider_m->save($find_save_array);
			$prv_id=$provider_m->id;
		}
		else
		{
			$prv_id=$data['Provider']['id'];
		}
		$provider_m->clear();
		return $prv_id;
	}

	private function _insertLocations($record_array=array())
	{
		$location_m = ClassRegistry::init('Location');
		$find_save_array = array(
					'name'=>($record_array['practicename']!='')?$record_array['practicename']:'none',
					'state'=>$record_array['state'],
					'zipcode'=>$record_array['zip'],
					'zipcode2'=>$record_array['zip4'],
					'county'=>$record_array['county'],
					'city'=>$record_array['city'],
					'address1'=>$record_array['address'],
					'address2'=>$record_array['suite'],
					'wheelchair_accessible'=>$record_array['handicap'],
					'longitude'=>$record_array['longitude'],
					'latitude'=>$record_array['latitude'],
					'cstm_desc_1'=>$record_array['customfield1desc'],
					'cstm_ind_1'=>$record_array['customfield1ind'],
					'cstm_desc_2'=>$record_array['customfield2desc'],
					'cstm_ind_2'=>$record_array['customfield2ind'],
					'cstm_desc_3'=>$record_array['customfield3desc'],
					'cstm_ind_3'=>$record_array['customfield3ind']
					);
		$data = $location_m->find('first',array('conditions'=>array('AND'=>array('name'=>($record_array['practicename']!='')?$record_array['practicename']:'none','zipcode'=>$record_array['zip'],'address1'=>$record_array['address'],'state'=>$record_array['state'],'city'=>$record_array['city']))));
		if(!isset($data['Location']['id']))
		{
			$location_m->create();
			$location_m->save($find_save_array);
			$id=$location_m->id;
		}
		else
		{
			$id=$data['Location']['id'];
		}
		$location_m->clear();
		return array($id);
	}

	private function _checkAndInsertSingleNameRecords($type='none',$csv_str='')
	{
		$values = explode(',', $csv_str);
		if(count($values) == 1 && $values[0]=='')
			return array();

		if(!in_array($type, array('Insurance','Countie','Language','Specialtie','Network')))
			return false;

		$model = ClassRegistry::init($type);
		$array_of_id = array();
		foreach($values as $name)
		{
			$name = strtolower($name);
			$data = $model->find('first',array('name'=>$name));
			if(!isset($data[$type]['id']))
			{
				$model->create();
				$model->save(array('name'=>$name));
				$array_of_id[]=$model->id;
			}
			else
			{
				$array_of_id[]=$data[$type]['id'];
			}

		}
		$model->clear();
		return $array_of_id;
	}


	/**
	 * converst single tdv line to array ,lowercase removing breaks and possible spaces and double quotes
	 */
	private function _convertToArray($tab_del_v_fields_string,$trimspace=true)
	{
		$field_array = explode("\t", $tab_del_v_fields_string);  
		$spc = $trimspace?' ':'l33t5';

		foreach($field_array as $k=>$v)
		{
			$field_array[$k] = strtolower(str_replace(array('"',$spc,"\r","\n","\r\n"),'',$field_array[$k]));
		}

		return $field_array;
	}
}
?>
