<?php
class SearchController extends AppController {
	public $helpers = array('Html', 'Form','Session');
	public $uses = array('Insurance','Language','Location','Provider','Specialtie');

	public function index() {
		$this->autoRender = false;


		$insurances 	= $this->Insurance->find('all');
		$languages 		= $this->Language->find('all');
		$locations 		= $this->Location->find('all');
		$providers 		= $this->Provider->find('all');
		$specialties 	= $this->Specialtie->find('all');

		$this->set('insurances', $insurances);
		$this->set('languages', $languages);
		$this->set('locations', $locations);
		$this->set('providers', $providers);
		$this->set('specialties', $specialties);
		$this->layout = 'search';
		$this->render('search1');
	}

	public function result()
	{
		$this->loadModel('Search');
		$request_data = $this->request->data;
		$this->Search->set($request_data);
		if($this->Search->validates())
		{
			$results = $this->Search->getResults($request_data['zipcode'],$request_data['distance'],$request_data);
		}
		else
		{
			$error = $this->Search->validationErrors;
			$err_field = key($error);
			$err_msg = $error[$err_field][0];

			$this->Session->setFlash($err_msg, 'default', array(), $err_field);
			//$this->Session->setFlash(array($err_field,$err_msg));
			return $this->redirect(array('action' => 'index'));
			//var_dump($this->Search->invalidFields());
			//exit;
		}

		$this->autoRender = false;
		$this->set('srch_filter',$request_data);
		$this->set('results', $results['providers']);
		$this->set('locations', $results['locations']);
		$this->set('coor', $results['coor_array']);
		$this->layout = 'search';
		$this->render('result1');
	}

	public function view()
	{
		$this->autoRender = false;

		$this->layout = 'search';
		$this->render('view1');
	}
	// public function view($id = null) {
 //        if (!$id) {
 //            throw new NotFoundException(__('Invalid post'));
 //        }

 //        $post = $this->Post->findById($id);
 //        if (!$post) {
 //            throw new NotFoundException(__('Invalid post'));
 //        }
 //        $this->set('post', $post);
 //    }

 //    public function add() {
 //        if ($this->request->is('post')) {
 //            $this->Post->create();
 //            if ($this->Post->save($this->request->data)) {
 //                $this->Session->setFlash(__('Your post has been saved.'));
 //                return $this->redirect(array('action' => 'index'));
 //            }
 //            $this->Session->setFlash(__('Unable to add your post.'));
 //        }
 //    }

 //    public function edit($id = null) {
	//     if (!$id) {
	//         throw new NotFoundException(__('Invalid post'));
	//     }

	//     $post = $this->Post->findById($id);
	//     if (!$post) {
	//         throw new NotFoundException(__('Invalid post'));
	//     }

	//     if ($this->request->is(array('post', 'put'))) {
	//         $this->Post->id = $id;
	//         if ($this->Post->save($this->request->data)) {
	//             $this->Session->setFlash(__('Your post has been updated.'));
	//             return $this->redirect(array('action' => 'index'));
	//         }
	//         $this->Session->setFlash(__('Unable to update your post.'));
	//     }

	//     if (!$this->request->data) {
	//         $this->request->data = $post;
	//     }
	// }
		
	// public function delete($id) {
	//     if ($this->request->is('get')) {
	//         throw new MethodNotAllowedException();
	//     }

	//     if ($this->Post->delete($id)) {
	//         $this->Session->setFlash(
	//             __('The post with id: %s has been deleted.', h($id))
	//         );
	//         return $this->redirect(array('action' => 'index'));
	//     }
	// }

}

?>