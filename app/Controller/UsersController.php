<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {


	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
	}

/**
 * login method
 *
 * @return void
 */
	public function login() {

		// echo AuthComponent::password('admin');

		if ($this->request->is('post')) {
			if($this->Auth->login()) {

				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('logins', $this->Auth->user('logins') + 1);
				$this->User->saveField('last_login', date('Y-m-d H:i:s'));

				if ($this->Auth->user('role') == 'customer') {
					return $this->redirect(array(
						'controller' => 'users',
						'action' => 'dashboard',
						'customer' => true,
						'admin' => false
					));
				} elseif ($this->Auth->user('role') == 'admin') {
					return $this->redirect(array(
						'controller' => 'users',
						'action' => 'dashboard',
						'manager' => false,
						'admin' => true
					));
				} else {
					$this->Session->setFlash('Login is incorrect');
				}
			} else {
				$this->Session->setFlash('Login is incorrect');
			}
		}
	}

/**
 * logout method
 *
 * @return void
 */
	public function logout() {
		$this->Session->setFlash('Good-Bye');
		return $this->redirect($this->Auth->logout());
	}

/**
 * customer_dashboard method
 *
 * @return void
 */
	public function customer_dashboard() {
	}

/**
 * admin_dashboard method
 *
 * @return void
 */

	public function admin_dashboard() {
	}

/**
 * admin_index method
 *
 * @throws NotFoundException
 * @return void
 */
	public function admin_index() {

		$this->Paginator = $this->Components->load('Paginator');

		$this->Paginator->settings = array(
			'User' => array(
				'recursive' => -1,
				'contain' => array(
				),
				'conditions' => array(
				),
				'order' => array(
					'Users.name' => 'ASC'
				),
				'limit' => 20,
				'paramType' => 'querystring',
			)
		);
		$users = $this->Paginator->paginate();
		$this->set(compact('users'));
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param int $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException('Invalid user');
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('The user has been saved');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The user could not be saved. Please, try again.');
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException('Invalid user');
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('The user has been saved');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The user could not be saved. Please, try again.');
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

/**
 * admin_password method
 *
 * @throws NotFoundException
 * @param int $id
 * @return void
 */
	public function admin_password($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException('Invalid user');
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('The user has been saved');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The user could not be saved. Please, try again.');
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
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
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException('Invalid user');
		}
		if ($this->User->delete()) {
			$this->Session->setFlash('User deleted');
			return $this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash('User was not deleted');
		return $this->redirect(array('action' => 'index'));
	}

}
