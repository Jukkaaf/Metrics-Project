<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Requirements Controller
 *
 * @property \App\Model\Table\RequirementsTable $Requirements
 */
class RequirementsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Projects']
        ];
        $this->set('requirements', $this->paginate($this->Requirements));
        $this->set('_serialize', ['requirements']);
    }

    /**
     * View method
     *
     * @param string|null $id Requirement id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $requirement = $this->Requirements->get($id, [
            'contain' => ['Projects']
        ]);
        $this->set('requirement', $requirement);
        $this->set('_serialize', ['requirement']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $requirement = $this->Requirements->newEntity();
        if ($this->request->is('post')) {
            $requirement = $this->Requirements->patchEntity($requirement, $this->request->data);
            if ($this->Requirements->save($requirement)) {
                $this->Flash->success(__('The requirement has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The requirement could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Requirements->Projects->find('list', ['limit' => 200]);
        $this->set(compact('requirement', 'projects'));
        $this->set('_serialize', ['requirement']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Requirement id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $requirement = $this->Requirements->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requirement = $this->Requirements->patchEntity($requirement, $this->request->data);
            if ($this->Requirements->save($requirement)) {
                $this->Flash->success(__('The requirement has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The requirement could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Requirements->Projects->find('list', ['limit' => 200]);
        $this->set(compact('requirement', 'projects'));
        $this->set('_serialize', ['requirement']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Requirement id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $requirement = $this->Requirements->get($id);
        if ($this->Requirements->delete($requirement)) {
            $this->Flash->success(__('The requirement has been deleted.'));
        } else {
            $this->Flash->error(__('The requirement could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
