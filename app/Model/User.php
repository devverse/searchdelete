<?php

class User extends AppModel {
	 public $validate = array(
        'username' => array(
            'rule' => 'notEmpty',
            'required' => true
        )
    );
}
?>