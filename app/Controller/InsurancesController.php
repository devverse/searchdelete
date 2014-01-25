<?php
class InsurancesController extends AppController {
	public $helpers = array('Html', 'Form','Session');

	public function index() {
		
		$posts =  $this->Insurance->find('all');
		$this->set('insurances', $posts);
	}
}

?>