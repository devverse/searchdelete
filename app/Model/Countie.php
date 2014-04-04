<?php

class Countie extends AppModel {
	 public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>