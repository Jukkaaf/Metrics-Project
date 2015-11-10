<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
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
        $project_id = $this->request->session()->read('selected_project')['id'];
        $this->paginate = [
            'contain' => ['Members'],
            'conditions' => array('Members.project_id' => $project_id)
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
        $project_id = $this->request->session()->read('selected_project')['id'];
        $members = $this->Workinghours->Members->find('list', ['limit' => 200, 'conditions' => array('Members.project_id' => $project_id)]);
        $this->set(compact('workinghour', 'members'));
        $this->set('_serialize', ['workinghour']);
    }
    
    private function addUploaded(){
        $report = $this->request->session()->read('report');
        $hours_count = count($report['actual_hours']);
        // if there are still working hours to add
        if($report['actual_hours_index'] < $hours_count - 1){
            // count the index in the session and reload the page with a new members info
            $report['actual_hours_index'] = $report['actual_hours_index'] + 1;
            $this->request->session()->write('report', $report);
            return $this->redirect(
                ['controller' => 'Workinghours', 'action' => 'add']
            );
        }
        else{
            $this->request->session()->delete('report');
            return $this->redirect(['action' => 'index']);
        }
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
        $project_id = $this->request->session()->read('selected_project')['id'];
        $members = $this->Workinghours->Members->find('list', ['limit' => 200, 'conditions' => array('Members.project_id' => $project_id)]);
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
    
    public function isAuthorized($user)
    {   
        $project_role = $this->request->session()->read('selected_project_role');
        //special rule for workinghours controller.
        // all members can add edit and delete workinghours
        if ($this->request->action === 'add' || $this->request->action === 'edit'
            || $this->request->action === 'delete') 
        {
            if($project_role != "notmember"){
                return True;
            }
            return False;
        }
        // if not trying to add edit delete
        return parent::isAuthorized($user);
    }
}
