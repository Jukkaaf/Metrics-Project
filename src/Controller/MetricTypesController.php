<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * MetricTypes Controller
 *
 * @property \App\Model\Table\MetricTypesTable $MetricTypes
 */
class MetricTypesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('metricTypes', $this->paginate($this->MetricTypes));
        $this->set('_serialize', ['metricTypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Metric Type id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $metricType = $this->MetricTypes->get($id, [
            'contain' => []
        ]);
        $this->set('metricType', $metricType);
        $this->set('_serialize', ['metricType']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $metricType = $this->MetricTypes->newEntity();
        if ($this->request->is('post')) {
            $metricType = $this->MetricTypes->patchEntity($metricType, $this->request->data);
            if ($this->MetricTypes->save($metricType)) {
                $this->Flash->success(__('The metric type has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The metric type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('metricType'));
        $this->set('_serialize', ['metricType']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Metric Type id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $metricType = $this->MetricTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $metricType = $this->MetricTypes->patchEntity($metricType, $this->request->data);
            if ($this->MetricTypes->save($metricType)) {
                $this->Flash->success(__('The metric type has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The metric type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('metricType'));
        $this->set('_serialize', ['metricType']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Metric Type id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $metricType = $this->MetricTypes->get($id);
        if ($this->MetricTypes->delete($metricType)) {
            $this->Flash->success(__('The metric type has been deleted.'));
        } else {
            $this->Flash->error(__('The metric type could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
