<?php

App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {
	public function missingController($error) {
		$this->controller->autoRender = false;
        $this->controller->beforeFilter();
        $this->controller->set('title_for_layout', 'Missing Controller');
        $this->controller->layout = 'blank';
        $this->controller->render('/Errors/missingcontroller');
        $this->controller->response->send();
    }

}

?>