<?php
use Cake\ORM\TableRegistry;
class ClientUsers extends AppModel {
	public $useTable = false;
	public $validate = array(
		'new_client_name' => array(
			'Cannot be blank. Must be Alpha Numeric Character' => array(
	                'rule'     => array('custom', '/^[a-z0-9 ]*$/i'),
	                'allowEmpty' => false
				),
            	'This client name already exists. Please input a distinct client name' => array(
	                'rule'=>array('checkclientSettings'),
            	)
               ));

	public function checkclientSettings()
	{
		$d = $this->data['ClientUsers'];
		$name = strtolower(str_replace(' ', '', $d['new_client_name']));
		$sql = "Select * from clients where name like '%{$name}%'";
		$records = $this->query($sql,false);

		if(count($records) > 0)
			return false;
		else
			return true;
	}

	public function getClientSettings($client_name='')
	{
		

		return $records;
	}

	public function addClientSettings($client_name='')
	{
		App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
		$pswdHsh 	= new SimplePasswordHasher();
		App::import('model','Client');
		$clients = new Client();
		App::import('model','User');
		$users = new User();

		$d 			= $this->data['ClientUsers'];
		$name 		= $d['new_client_name'];
		$sname 		= strtolower(str_replace(' ', '',$name));
		$password 	= $pswdHsh->hash(str_replace(array('a','e','i','o','u'),'', $sname).'2014$');
		$cli 		= array();
		$usr 		= array();

		$cli['company_name'] 		= $name;
		$cli['name'] 				= $sname;
		$cli['view_prefix_name'] 	= $sname.'_';
		$cli['cake_db_config'] 		= $sname;
		$cli['search_type'] 		= 1;
		$cli['asset_folder_name'] 	= $sname;
		$cli['migrating'] 			= 0;
		$cli['disable ']			= 1;

		$saved_client = $clients->save($cli);

		$client_id = $saved_client['Client']['id'];

		$usr['username'] 	= $sname.'_usr';
		$usr['password'] 	= $password;
		$usr['type'] 		= 1;
		$usr['client_id'] 	= $client_id;

		$saved_user = $users->save($usr);

		$status = $this->adddatabase($sname);

		if($status && isset($saved_user['User']['id']) && isset($saved_client['Client']['id']))
			return true;
		else
			return false;
	}

	public function adddatabase($dbname)
	{
		App::uses('File', 'Utility');

		$file = new File('../../app/Config/database.php', false, 0644);
		$file->open('r+');

		$old_config = $file->read();
		
		$added_config = "public \${$dbname} = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'aspire5610z',
		'database' => '{$dbname}_db',
		'prefix' => '',
		//'encoding' => 'utf8',
	);

}//stringidentifier";

		$new_config = str_replace('}//stringidentifier', $added_config, $old_config);

		$status = $file->write($new_config,'w');

		$file->close();

		return $status;
	}

}
?>
