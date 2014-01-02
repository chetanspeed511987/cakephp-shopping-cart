<?php
App::uses('AppController', 'Controller');
/**
 * Tags Controller
 *
 * @property Tag $Tag
 */
class TagsController extends AppController {

/**
 * Array containing the names of components this controller uses. 
 *
 * @var array
 */
	public $components = array('Paginator');


/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {

		$this->Paginator->settings = array(
			'recursive' => -1,
			'order' => array(
				'Tag.name' => 'ASC'
			),
			'limit' => 100,
		);

		$this->set('tags', $this->Paginator->paginate('Tag'));
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param int $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Tag->exists($id)) {
			throw new NotFoundException('Invalid tag');
		}
		$options = array('conditions' => array('Tag.id' => $id));
		$this->set('tag', $this->Tag->find('first', $options));
	}


/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Tag->create();
			if ($this->Tag->save($this->request->data)) {
				$this->Session->setFlash('The tag has been saved.');
				return $this->redirect($this->referer());
				// return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The tag could not be saved. Please, try again.');
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param int $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Tag->exists($id)) {
			throw new NotFoundException('Invalid tag');
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Tag->save($this->request->data)) {
				$this->Session->setFlash('The tag has been saved.');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The tag could not be saved. Please, try again.');
			}
		} else {
			$options = array('conditions' => array('Tag.id' => $id));
			$this->request->data = $this->Tag->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param int $id 
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Tag->id = $id;
		if (!$this->Tag->exists()) {
			throw new NotFoundException('Invalid tag');
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tag->delete()) {
			$this->Session->setFlash('The tag has been deleted.');
		} else {
			$this->Session->setFlash('The tag could not be deleted. Please, try again.');
		}
		return $this->redirect(array('action' => 'index'));
	}

}
