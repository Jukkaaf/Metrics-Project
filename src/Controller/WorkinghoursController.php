<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Workinghours Controller
 *
 * @property \App\Model\Table\WorkinghoursTable $Workinghours
 */
class WorkinghoursController extends AppController
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
        $this->set('workinghours', $this->paginate($this->Workinghours));
        $this->set('_serialize', ['workinghours']);
    }

    /**
     * View method
     *
     * @param string|null $id Workinghour id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $workinghour = $this->Workinghours->get($id, [
            'contain' => ['Members']
        ]);
        $this->set('workinghour', $workinghour);
        $this->set('_serialize', ['workinghour']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $workinghour = $this->Workinghours->newEntity();
        if ($this->request->is('post')) {
            $workinghour = $this->Workinghours->patchEntity($workinghour, $this->request->data);
            if ($this->Workinghours->save($workinghour)) {
                $this->Flash->success(__('The workinghour has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The workinghour could not be saved. Please, try again.'));
            }
        }
        $members = $this->Workinghours->Members->find('list', ['limit' => 200]);
        $this->set(compact('workinghour', 'members'));
        $this->set('_serialize', ['workinghour']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Workinghour id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $workinghour = $this->Workinghours->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $workinghour = $this->Workinghours->patchEntity($workinghour, $this->request->data);
            if ($this->Workinghours->save($workinghour)) {
                $this->Flash->success(__('The workinghour has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The workinghour could not be saved. Please, try again.'));
            }
        }
        $members = $this->Workinghours->Members->find('list', ['limit' => 200]);
        $this->set(compact('workinghour', 'members'));
        $this->set('_serialize', ['workinghour']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Workinghour id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $workinghour = $this->Workinghours->get($id);
        if ($this->Workinghours->delete($workinghour)) {
            $this->Flash->success(__('The workinghour has been deleted.'));
        } else {
            $this->Flash->error(__('The workinghour could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
