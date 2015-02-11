<?php

class Client extends AppModel {

	public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>