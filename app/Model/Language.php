<?php

class Language extends AppModel {
	 public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>