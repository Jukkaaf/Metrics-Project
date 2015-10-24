<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Weeklyreports Controller
 *
 * @property \App\Model\Table\WeeklyreportsTable $Weeklyreports
 */
class WeeklyreportsController extends AppController
{
    
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Upload');
    }
    
    // uploading new weeklyreports as txt files
    public function upload(){
        if ( !empty( $this->request->data ) ) {
            $file_content = $this->Upload->send($this->request->data['uploadfile']);
            $this->Weeklyreports->saveUploadedReport($file_content);
        }
    }
    
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
        $this->set('weeklyreports', $this->paginate($this->Weeklyreports));
        $this->set('_serialize', ['weeklyreports']);
    }

    /**
     * View method
     *
     * @param string|null $id Weeklyreport id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $weeklyreport = $this->Weeklyreports->get($id, [
            'contain' => ['Projects']
        ]);
        $this->set('weeklyreport', $weeklyreport);
        $this->set('_serialize', ['weeklyreport']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $weeklyreport = $this->Weeklyreports->newEntity();
        if ($this->request->is('post')) {
            //print_r($this->request->data['date']);
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $this->request->data);
            if ($this->Weeklyreports->save($weeklyreport)) {
                $this->Flash->success(__('The weeklyreport has been saved.'));
                //return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weeklyreport could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Weeklyreports->Projects->find('list', ['limit' => 200]);
        $this->set(compact('weeklyreport', 'projects'));
        $this->set('_serialize', ['weeklyreport']);    
    }

    /**
     * Edit method
     *
     * @param string|null $id Weeklyreport id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $weeklyreport = $this->Weeklyreports->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $this->request->data);
            if ($this->Weeklyreports->save($weeklyreport)) {
                $this->Flash->success(__('The weeklyreport has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weeklyreport could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Weeklyreports->Projects->find('list', ['limit' => 200]);
        $this->set(compact('weeklyreport', 'projects'));
        $this->set('_serialize', ['weeklyreport']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Weeklyreport id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $weeklyreport = $this->Weeklyreports->get($id);
        if ($this->Weeklyreports->delete($weeklyreport)) {
            $this->Flash->success(__('The weeklyreport has been deleted.'));
        } else {
            $this->Flash->error(__('The weeklyreport could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
