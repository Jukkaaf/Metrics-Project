<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
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
            'contain' => ['Members', 'Worktypes'],
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
        $project_id = $this->request->session()->read('selected_project')['id'];
        $workinghour = $this->Workinghours->get($id, [
            'contain' => ['Members', 'Worktypes'],
            'conditions' => array('Members.project_id' => $project_id)
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
            
            $workinghour['member_id'] = $this->request->session()->read('selected_project_memberid');
            
            if ($this->Workinghours->save($workinghour)) {
                $this->Flash->success(__('The workinghour has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The workinghour could not be saved. Please, try again.'));
            }
        }
        $project_id = $this->request->session()->read('selected_project')['id'];
        $worktypes = $this->Workinghours->Worktypes->find('list', ['limit' => 200]);
        $members = $this->Workinghours->Members->find('list', ['limit' => 200, 'conditions' => array('Members.project_id' => $project_id)]);
        $this->set(compact('workinghour', 'members', 'worktypes'));
        $this->set('_serialize', ['workinghour']);
    }
    
    public function adddev()
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
        $worktypes = $this->Workinghours->Worktypes->find('list', ['limit' => 200]);
        $now = Time::now();
        /*
        $members = $this->Workinghours->Members->find('list', ['limit' => 200, 
            'conditions' => array('Members.project_id' => $project_id, 'OR' => array('Members.ending_date >' => $now, 'Members.ending_date' => NULL))]);
        */
        $members = $this->Workinghours->Members->find('list', ['limit' => 200])
                                                ->where(['Members.project_id' => $project_id, 'Members.project_role !=' => 'supervisor', 'Members.ending_date >' => $now])
                                                ->orWhere(['Members.project_id' => $project_id, 'Members.project_role !=' => 'supervisor', 'Members.ending_date IS' => NULL]);
        $this->set(compact('workinghour', 'members', 'worktypes'));
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
        $project_id = $this->request->session()->read('selected_project')['id'];
        $workinghour = $this->Workinghours->get($id, [
            'contain' => ['Members', 'Worktypes'],
            'conditions' => array('Members.project_id' => $project_id)
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
        $worktypes = $this->Workinghours->Worktypes->find('list', ['limit' => 200]);
        $now = Time::now();
        $members = $this->Workinghours->Members->find('list', ['limit' => 200])
                                                ->where(['Members.project_id' => $project_id, 'Members.project_role !=' => 'supervisor', 'Members.ending_date >' => $now])
                                                ->orWhere(['Members.project_id' => $project_id, 'Members.project_role !=' => 'supervisor', 'Members.ending_date IS' => NULL]);
        $this->set(compact('workinghour', 'members', 'worktypes'));
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
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }
        
        $project_role = $this->request->session()->read('selected_project_role');
        
        if ($this->request->action === 'add') 
        {
            // supervisor cannot have workinghours, and the add function simply takes the member_id of the current user
            if($project_role != "notmember" || $project_role == "supervisor"){
                return True;
            }
            return False;
        }
        // developers can only edit and delete their own workinghours
        if ($this->request->action === 'edit' || $this->request->action === 'delete') 
        {
            if($project_role == "developer"){
                $query = $this->Workinghours
                    ->find()
                    ->select(['member_id'])
                    ->where(['id =' => $this->request->pass[0]])
                    ->toArray();
                if($query[0]->member_id == $user['id']){
                    return True;
                }
                return False;
            }
        }

        //special rule for workinghours controller.
        // all members can add edit and delete workinghours
        if ($this->request->action === 'adddev' || $this->request->action === 'edit'
            || $this->request->action === 'delete') 
        {
            if($project_role == "manager" || $project_role == "supervisor"){
                return True;
            }
            return False;
        }
        // if not trying to add edit delete
        return parent::isAuthorized($user);
    }
}
