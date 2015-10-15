<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Works Controller
 *
 * @property \App\Model\Table\WorksTable $Works
 */
class WorksController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Members']
        ];
        $this->set('works', $this->paginate($this->Works));
        $this->set('_serialize', ['works']);
    }

    /**
     * View method
     *
     * @param string|null $id Work id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $work = $this->Works->get($id, [
            'contain' => ['Members']
        ]);
        $this->set('work', $work);
        $this->set('_serialize', ['work']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $work = $this->Works->newEntity();
        if ($this->request->is('post')) {
            $work = $this->Works->patchEntity($work, $this->request->data);
            if ($this->Works->save($work)) {
                $this->Flash->success(__('The work has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The work could not be saved. Please, try again.'));
            }
        }
        $members = $this->Works->Members->find('list', ['limit' => 200]);
        $this->set(compact('work', 'members'));
        $this->set('_serialize', ['work']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Work id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $work = $this->Works->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $work = $this->Works->patchEntity($work, $this->request->data);
            if ($this->Works->save($work)) {
                $this->Flash->success(__('The work has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The work could not be saved. Please, try again.'));
            }
        }
        $members = $this->Works->Members->find('list', ['limit' => 200]);
        $this->set(compact('work', 'members'));
        $this->set('_serialize', ['work']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Work id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $work = $this->Works->get($id);
        if ($this->Works->delete($work)) {
            $this->Flash->success(__('The work has been deleted.'));
        } else {
            $this->Flash->error(__('The work could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
