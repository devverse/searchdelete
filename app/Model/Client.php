<?php

class Client extends AppModel {

	public $useTable = 'temptables';

	public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>