<?php

class Providertype extends AppModel {
	 public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>