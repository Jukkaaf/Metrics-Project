<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Worktypes Controller
 *
 * @property \App\Model\Table\WorktypesTable $Worktypes
 */
class WorktypesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('worktypes', $this->paginate($this->Worktypes));
        $this->set('_serialize', ['worktypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Worktype id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $worktype = $this->Worktypes->get($id, [
            'contain' => ['Workinghours']
        ]);
        $this->set('worktype', $worktype);
        $this->set('_serialize', ['worktype']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $worktype = $this->Worktypes->newEntity();
        if ($this->request->is('post')) {
            $worktype = $this->Worktypes->patchEntity($worktype, $this->request->data);
            if ($this->Worktypes->save($worktype)) {
                $this->Flash->success(__('The worktype has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The worktype could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('worktype'));
        $this->set('_serialize', ['worktype']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Worktype id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $worktype = $this->Worktypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $worktype = $this->Worktypes->patchEntity($worktype, $this->request->data);
            if ($this->Worktypes->save($worktype)) {
                $this->Flash->success(__('The worktype has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The worktype could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('worktype'));
        $this->set('_serialize', ['worktype']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Worktype id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $worktype = $this->Worktypes->get($id);
        if ($this->Worktypes->delete($worktype)) {
            $this->Flash->success(__('The worktype has been deleted.'));
        } else {
            $this->Flash->error(__('The worktype could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function isAuthorized($user)
    {      
        // Only admins can view add edit or delete 
        // But it is not advised since the code depends on the metric types
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }
}
