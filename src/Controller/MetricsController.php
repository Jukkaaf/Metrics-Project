<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Metrics Controller
 *
 * @property \App\Model\Table\MetricsTable $Metrics
 */
class MetricsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Projects', 'Metrictypes', 'Weeklyreports']
        ];
        $this->set('metrics', $this->paginate($this->Metrics));
        $this->set('_serialize', ['metrics']);
    }

    /**
     * View method
     *
     * @param string|null $id Metric id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $metric = $this->Metrics->get($id, [
            'contain' => ['Projects', 'Metrictypes', 'Weeklyreports']
        ]);
        $this->set('metric', $metric);
        $this->set('_serialize', ['metric']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $metric = $this->Metrics->newEntity();
        if ($this->request->is('post')) {
            $metric = $this->Metrics->patchEntity($metric, $this->request->data);
            
            // add the session project_id to the metric
            if($this->request->session()->check('selected_project')){
                $selected_project = $this->request->session()->read('selected_project');
                $metric['project_id'] = $selected_project['id'];
            }
            
            if ($this->Metrics->save($metric)) {
                $this->Flash->success(__('The metric has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The metric could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Metrics->Projects->find('list', ['limit' => 200]);
        $metrictypes = $this->Metrics->Metrictypes->find('list', ['limit' => 200]);
        $weeklyreports = $this->Metrics->Weeklyreports->find('list', ['limit' => 200]);
        $this->set(compact('metric', 'projects', 'metrictypes', 'weeklyreports'));
        $this->set('_serialize', ['metric']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Metric id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $metric = $this->Metrics->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $metric = $this->Metrics->patchEntity($metric, $this->request->data);
            
            // add the session project_id to the metric
            if($this->request->session()->check('selected_project')){
                $selected_project = $this->request->session()->read('selected_project');
                $metric['project_id'] = $selected_project['id'];
            }
            
            if ($this->Metrics->save($metric)) {
                $this->Flash->success(__('The metric has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The metric could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Metrics->Projects->find('list', ['limit' => 200]);
        $metrictypes = $this->Metrics->Metrictypes->find('list', ['limit' => 200]);
        $weeklyreports = $this->Metrics->Weeklyreports->find('list', ['limit' => 200]);
        $this->set(compact('metric', 'projects', 'metrictypes', 'weeklyreports'));
        $this->set('_serialize', ['metric']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Metric id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $metric = $this->Metrics->get($id);
        if ($this->Metrics->delete($metric)) {
            $this->Flash->success(__('The metric has been deleted.'));
        } else {
            $this->Flash->error(__('The metric could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
