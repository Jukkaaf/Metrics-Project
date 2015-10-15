<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * WeeklyReports Controller
 *
 * @property \App\Model\Table\WeeklyReportsTable $WeeklyReports
 */
class WeeklyReportsController extends AppController
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
        $this->set('weeklyReports', $this->paginate($this->WeeklyReports));
        $this->set('_serialize', ['weeklyReports']);
    }

    /**
     * View method
     *
     * @param string|null $id Weekly Report id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $weeklyReport = $this->WeeklyReports->get($id, [
            'contain' => ['Projects']
        ]);
        $this->set('weeklyReport', $weeklyReport);
        $this->set('_serialize', ['weeklyReport']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $weeklyReport = $this->WeeklyReports->newEntity();
        if ($this->request->is('post')) {
            $weeklyReport = $this->WeeklyReports->patchEntity($weeklyReport, $this->request->data);
            if ($this->WeeklyReports->save($weeklyReport)) {
                $this->Flash->success(__('The weekly report has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weekly report could not be saved. Please, try again.'));
            }
        }
        $projects = $this->WeeklyReports->Projects->find('list', ['limit' => 200]);
        $this->set(compact('weeklyReport', 'projects'));
        $this->set('_serialize', ['weeklyReport']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Weekly Report id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $weeklyReport = $this->WeeklyReports->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyReport = $this->WeeklyReports->patchEntity($weeklyReport, $this->request->data);
            if ($this->WeeklyReports->save($weeklyReport)) {
                $this->Flash->success(__('The weekly report has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weekly report could not be saved. Please, try again.'));
            }
        }
        $projects = $this->WeeklyReports->Projects->find('list', ['limit' => 200]);
        $this->set(compact('weeklyReport', 'projects'));
        $this->set('_serialize', ['weeklyReport']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Weekly Report id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $weeklyReport = $this->WeeklyReports->get($id);
        if ($this->WeeklyReports->delete($weeklyReport)) {
            $this->Flash->success(__('The weekly report has been deleted.'));
        } else {
            $this->Flash->error(__('The weekly report could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
