<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Metrictypes Controller
 *
 * @property \App\Model\Table\MetrictypesTable $Metrictypes
 */
class MetrictypesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('metrictypes', $this->paginate($this->Metrictypes));
        $this->set('_serialize', ['metrictypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Metrictype id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $metrictype = $this->Metrictypes->get($id, [
            'contain' => ['Metrics']
        ]);
        $this->set('metrictype', $metrictype);
        $this->set('_serialize', ['metrictype']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $metrictype = $this->Metrictypes->newEntity();
        if ($this->request->is('post')) {
            $metrictype = $this->Metrictypes->patchEntity($metrictype, $this->request->data);
            if ($this->Metrictypes->save($metrictype)) {
                $this->Flash->success(__('The metrictype has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The metrictype could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('metrictype'));
        $this->set('_serialize', ['metrictype']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Metrictype id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $metrictype = $this->Metrictypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $metrictype = $this->Metrictypes->patchEntity($metrictype, $this->request->data);
            if ($this->Metrictypes->save($metrictype)) {
                $this->Flash->success(__('The metrictype has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The metrictype could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('metrictype'));
        $this->set('_serialize', ['metrictype']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Metrictype id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $metrictype = $this->Metrictypes->get($id);
        if ($this->Metrictypes->delete($metrictype)) {
            $this->Flash->success(__('The metrictype has been deleted.'));
        } else {
            $this->Flash->error(__('The metrictype could not be deleted. Please, try again.'));
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
