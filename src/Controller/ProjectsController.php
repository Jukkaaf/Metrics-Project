<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\MemberController;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
/**
 * Projects Controller
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 */
class ProjectsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $project_list = $this->request->session()->read('project_list');
        
        $this->paginate = [
            'conditions' => array('id IN' => $project_list)
        ];
        
        $this->set('projects', $this->paginate($this->Projects));
        $this->set('_serialize', ['projects']);
    }
      
    /**
     * View method
     *
     * @param string|null $id Project id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => ['Members', 'Metrics', 'Weeklyreports']
        ]);
        $this->set('project', $project);
        $this->set('_serialize', ['project']);
        
        if($this->request->session()->read('selected_project')['id'] != $project['id']){
            $this->request->session()->write('selected_project', $project);
            // remove the all data from the weeklyreport form if any exists
            $this->request->session()->delete('current_weeklyreport');
            $this->request->session()->delete('current_metrics');
            $this->request->session()->delete('current_weeklyhours');
        }  
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $project = $this->Projects->newEntity();
        if ($this->request->is('post')) {
            $project = $this->Projects->patchEntity($project, $this->request->data);
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project has been saved.'));
                if($this->Auth->user('role') != "admin"){
                    // since the project was saved, we now have to add the creator as a supervisor member
                    $Members = TableRegistry::get('Members');
                    $Member = $Members->newEntity();
                    $Member['user_id'] = $this->Auth->user('id');
                    $Member['project_id'] = $project['id'];
                    $Member['project_role'] = "supervisor";
                    if (!$Members->save($Member)) {
                        $this->Flash->error(__('The project was saved, but we were not able to add you as a member'));
                    }
                }
                return $this->redirect(['action' => 'index']);
            } 
            else {
                $this->Flash->error(__('The project could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('project'));
        $this->set('_serialize', ['project']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Project id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->patchEntity($project, $this->request->data);
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project has been saved.'));
                return $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->error(__('The project could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('project'));
        $this->set('_serialize', ['project']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Project id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $project = $this->Projects->get($id);
        if ($this->Projects->delete($project)) {
            $this->Flash->success(__('The project has been deleted.'));
        } else {
            $this->Flash->error(__('The project could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    // this allows anyone to go to the frontpage
    public function beforeFilter(\Cake\Event\Event $event)
    {   
        // we now have to do stuff in isauthorized in index, so this will be removed
        //$this->Auth->allow(['index']);
    }
    
    public function isAuthorized($user)
    {      
        // Admin can access every action
        /*
        if (isset($user['role']) && $user['role'] === 'admin') {
            $this->request->session()->write('selected_project_role', "admin");
            $this->request->session()->write('selected_project_memberid', -1);
            return true;
        }
        */
        // Inactive can only do what users who are not members can
        if (isset($user['role']) && $user['role'] === 'inactive') {
            return False;
        }
        
        // the admin can see all the projects
        if ($this->request->action === 'index' && $user['role'] === 'admin'){
            $query = $this->Projects
                ->find()
                ->select(['id'])
                ->toArray();
            
            $project_list = array();
            foreach($query as $temp){
                $project_list[] = $temp->id;
            }
            
            $this->request->session()->write('is_admin', True);
            $this->request->session()->write('project_list', $project_list);
            return True;
        } 
        
        if ($this->request->action === 'index'){    
            $time = Time::now();
            $members = TableRegistry::get('Members');    

            $query = $members
                ->find()
                ->select(['project_id', 'ending_date', 'project_role'])
                ->where(['user_id =' => $this->Auth->user('id')])
                ->toArray();
            
            $is_supervisor = False;
            $project_list = array();
            foreach($query as $temp){
                if($temp->ending_date < $time || $temp->ending_date == NULL){
                    $project_list[] = $temp->project_id;
                    if($temp->project_role == 'supervisor'){
                        $is_supervisor = True;
                    }
                }
            }
            // add public projects to the list
            $query2 = $this->Projects
                ->find()
                ->select(['id'])
                ->where(['is_public' => 1])
                ->toArray();
            
            foreach($query2 as $temp){
                $project_list[] = $temp->id;
            }
            
            $this->request->session()->write('is_supervisor', $is_supervisor);
            $this->request->session()->write('project_list', $project_list);
            return True;
        }  
        
        if ($this->request->action === 'view') 
        {   
            // this is where we figure out what role the user has in the project
            $time = Time::now();
            $project_role = "";
            $project_memberid = -1;
            // what kind of member is the user
            $members = TableRegistry::get('Members');    

            $query = $members
                ->find()
                ->select(['project_role', 'id', 'ending_date'])
                ->where(['user_id =' => $this->Auth->user('id'), 'project_id =' => $this->request->pass[0]])
                ->toArray();

            // for loop goes through all the memberships that this user has for this project
            // its most likely just 1, but since it has not been limited to that we must check for all possibilities
            // the idea is that the highest membership is saved, 
            // so if he or she is a developer and a supervisor, we save the latter
            foreach($query as $temp){
                // if supervisor, overwrite all other memberships
                if($temp->project_role == "supervisor" && ($temp->ending_date > $time || $temp->ending_date == NULL)){
                    $project_role = $temp->project_role;
                    $project_memberid = $temp->id;
                }
                // if the user is a manager in the project 
                // but we have not yet found out that he or she is a supervisor
                // if dev or null then it gets overwritten
                elseif($temp->project_role == "manager" && $project_role != "supervisor" && ($temp->ending_date > $time || $temp->ending_date == NULL)){
                    $project_role = $temp->project_role;
                    $project_memberid = $temp->id;
                }
                // if we have not found out that the user is a manager or a supervisor
                elseif($project_role != "supervisor" && $project_role != "manager" && ($temp->ending_date > $time || $temp->ending_date == NULL)){
                    $project_role = $temp->project_role;
                    $project_memberid = $temp->id;
                }      
            }
            
            if($this->Auth->user('role') == "admin"){
                $project_role = "admin";
            }
            elseif($project_role == ""){
                $project_role = "notmember";
            }


            $this->request->session()->write('selected_project_role', $project_role);
            $this->request->session()->write('selected_project_memberid', $project_memberid);
            // if the user is not a member of the project return false
            // unless the project is public
            if($project_role == "notmember"){  
                $query = $this->Projects
                    ->find()
                    ->select(['is_public'])
                    ->where(['id' => $this->request->pass[0]])
                    ->toArray();          
                if($query[0]->is_public == 1){
                    return True;
                }
                else{
                    return False;
                }    
            }
            else{
                return True;
            }
        }

        
        $project_role = $this->request->session()->read('selected_project_role');
        
        // supervisors can add new projects
        // This has its own query because if the user is a member of multiple projects
        // his current role might not be his highest one 
        if ($this->request->action === 'add') 
        {
            if($this->Auth->user('role') == "admin"){
               return True; 
            }
            
            $members = TableRegistry::get('Members');
            
            $query = $members
                ->find()
                ->select(['project_role'])
                ->where(['user_id =' => $user['id']])
                ->toArray();

            foreach($query as $temp){
                if($temp->project_role == "supervisor"){
                    return True;
                }
            }
        }

        // supervisors can edit their own projects
        if ($this->request->action === 'edit' || $this->request->action === 'delete' ) 
        {
            if($project_role == "supervisor" || $project_role == "admin"){
                return True;
            }
        }
        //return parent::isAuthorized($user);
        
        // Default deny
        return false;
    }
}
