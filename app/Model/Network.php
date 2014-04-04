<?php

class Network extends AppModel {
	 public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>