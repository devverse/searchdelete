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

	public function updateClientSettings($data=array())
	{
		if(!isset($data['id']) || !isset($data['name']) || !isset($data['view_prefix_name']) || !isset($data['asset_folder_name']) || !isset($data['disable']))
			return false;

		$name = $data['name'];
		$update_array = array();
		$update_array['id'] = $data['id'];
		$update_array['view_prefix_name'] = $data['view_prefix_name'] == '' ? '' : $name.'_';
		$update_array['asset_folder_name'] = $data['asset_folder_name'] == '' ? '' : $name;
		$update_array['disable'] = $data['disable'] == '0' ? '0' : '1';

		App::import('model','Client');
		$client = new Client();

		$saved_client = $client->save($update_array);

		return ($saved_client)?true:false;
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
		$cli['view_prefix_name'] 	= '';
		$cli['cake_db_config'] 		= $sname;
		$cli['search_type'] 		= 1;
		$cli['asset_folder_name'] 	= '';
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

		$sql = 'Create DATABASE '.$dbname.'_db';
		$records = $this->query($sql,false);

		return $status;
	}

}
?>
