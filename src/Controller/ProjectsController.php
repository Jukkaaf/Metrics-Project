<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\MemberController;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
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
            
            print_r($project['id']);
            // create a folder for the project
            
            //disabled since uplading reports is not currently supported
            //$path = ROOT . DS . 'reports' . DS . $this->request->data['project_name'];
            //$dir = new Folder($path, true, 0755);
            /*
            if(!is_null($dir->path)){
                if ($this->Projects->save($project)) {
                    $this->Flash->success(__('The project has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } 
                else {
                    $this->Flash->error(__('The project could not be saved. Please, try again.'));
                }
            }
            else{
                $this->Flash->error(__('Could not create a folder for the project. Please change the name'));
            }*/
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
                return $this->redirect(['action' => 'index']);
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
        $this->Auth->allow(['index']);
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
        
        if ($this->request->action === 'view') 
        {   
            // this is where we figure out what role the user has in the project
            
            $project_role = "";
            $project_memberid = -1;
            // what kind of member is the user
            $members = TableRegistry::get('Members');    

            $query = $members
                ->find()
                ->select(['project_role', 'id'])
                ->where(['user_id =' => $this->Auth->user('id'), 'project_id =' => $this->request->pass[0]])
                ->toArray();

            foreach($query as $temp){
                if($temp->project_role == "supervisor"){
                    $project_role = $temp->project_role;
                    $project_memberid = $temp->id;
                }
                elseif($temp->project_role == "manager" && $project_role != "supervisor"){
                    $project_role = $temp->project_role;
                    $project_memberid = $temp->id;
                }
                elseif($project_role != "supervisor" && $project_role != "manager"){
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
            if($project_role == "notmember"){
                return False;
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
