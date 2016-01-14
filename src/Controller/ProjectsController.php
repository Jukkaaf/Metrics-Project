<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\MemberController;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ProjectsController extends AppController
{
    public function index()
    {
        // list of the projects that should be shown in the front page
        $project_list = $this->request->session()->read('project_list');
        
        if($project_list != NULL){
            $this->paginate = [
                'conditions' => array('id IN' => $project_list),
                'order' => ['project_name' => 'ASC']
            ];   
        }
        else{
            $this->paginate = [
                'conditions' => array('id' => NULL),
                'order' => ['project_name' => 'ASC']
            ];     
        }
        $this->set('projects', $this->paginate($this->Projects));
        $this->set('_serialize', ['projects']);
    }
    
    // function that is run when you select a project
    public function view($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => ['Members', 'Metrics', 'Weeklyreports']
        ]);
        $this->set('project', $project);
        $this->set('_serialize', ['project']);
        
        // if the selected project is a new one
        if($this->request->session()->read('selected_project')['id'] != $project['id']){
            // write the new id 
            $this->request->session()->write('selected_project', $project);
            // remove the all data from the weeklyreport form if any exists
            $this->request->session()->delete('current_weeklyreport');
            $this->request->session()->delete('current_metrics');
            $this->request->session()->delete('current_weeklyhours');
        }  
    }
    
    public function statistics()
    {
        // get the limits from the sidebar if changes were submitted
        if ($this->request->is('post')) {
            $data = $this->request->data;
            
            $statistics_limits['weekmin'] = $data['weekmin'];
            $statistics_limits['weekmax'] = $data['weekmax'];
            $statistics_limits['year'] = $data['year'];
            
            $this->request->session()->write('statistics_limits', $statistics_limits);
            // reload page
            $page = $_SERVER['PHP_SELF'];
        }
        // current default settings
        if(!$this->request->session()->check('statistics_limits')){
            $time = Time::now();
            // magic numbers for the springs project work course
            $statistics_limits['weekmin'] = 5;
            $statistics_limits['weekmax'] =  23;
            
            $statistics_limits['year'] = $time->year;
            
            $this->request->session()->write('statistics_limits', $statistics_limits);
        }
        // load the limits to a variable
        $statistics_limits = $this->request->session()->read('statistics_limits');
        // function in the projects table "ProjectsTable.php"
        // return the list of public projects
        $publicProjects = $this->Projects->getPublicProjects();
        $projects = array();
        // the weeklyreport weeks and the total weeklyhours duration is loaded for all projects
        // functions in "ProjectsTable.php"
        foreach($publicProjects as $project){
            $project['reports'] = $this->Projects->getWeeklyreportWeeks($project['id'], 
                                  $statistics_limits['weekmin'], $statistics_limits['weekmax'], $statistics_limits['year']);
            $project['duration'] = $this->Projects->getWeeklyhoursDuration($project['id']);
            $projects[] = $project;
        }
        // the projects and their data are made visible in the "statistics.php" page
        $this->set(compact('projects'));
        $this->set('_serialize', ['projects']);
    }
    
    // empty function, because nothing needs to be done but the function has to exist
    public function faq()
    {

    }
    
    public function add()
    {
        $project = $this->Projects->newEntity();
        if ($this->request->is('post')) {
            // data loaded from the form
            $project = $this->Projects->patchEntity($project, $this->request->data);
            // the current date is put in the project object
            $time = Time::now();
            $project['created_on'] = $time;
            
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project has been saved.'));
                // if the project was not saved by an admin
                if($this->Auth->user('role') != "admin"){
                    // the user is added to the project as a supervisor
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

    public function edit($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            // data from the form
            $project = $this->Projects->patchEntity($project, $this->request->data);
            // updated_on date is placed automatically
            $time = Time::now();
            $project['updated_on'] = $time;
            
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
        // if the current user is not a logged in user
        if(!$this->Auth->user()){
            // publuc projects are added to the project list
            $query2 = $this->Projects
                ->find()
                ->select(['id'])
                ->where(['is_public' => 1])
                ->toArray();     
            $project_list = array();
            foreach($query2 as $temp){
                $project_list[] = $temp->id;
            }
            $this->request->session()->write('project_list', $project_list);
            // allow access to index
            $this->Auth->allow(['index']);
        }
        // statistics and faq are open pages to everyone
        $this->Auth->allow(['statistics']);
        $this->Auth->allow(['faq']);
    }
    
    public function isAuthorized($user)
    {      
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
            $this->request->session()->write('project_memberof_list', $project_list);
            return True;
        } 
        
        if ($this->request->action === 'index'){    
            $time = Time::now();
            $members = TableRegistry::get('Members');    
            // find all the projects that the user is a member in
            $query = $members
                ->find()
                ->select(['project_id', 'ending_date', 'project_role'])
                ->where(['user_id =' => $this->Auth->user('id')])
                ->toArray();
            
            
            $is_supervisor = False;
            $project_list = array();
            foreach($query as $temp){
                // check if the user is a supervisor in any of the projects
                // and add the projects to the projectlist
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
            
            $this->request->session()->write('is_supervisor', $is_supervisor);
            $this->request->session()->write('project_memberof_list', $project_list);        
            foreach($query2 as $temp){
                $project_list[] = $temp->id;
            } 
            $this->request->session()->write('project_list', $project_list);
            
            return True;
        }  
        
        // authorization for the selected project
        if ($this->request->action === 'view') 
        {   
            $time = Time::now();
            $project_role = "";
            $project_memberid = -1;
            // what kind of member is the user
            $members = TableRegistry::get('Members');    
            // load all the memberships that the user has for the selected project
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
            // if the user is a admin, he is automatically a admin of the project
            if($this->Auth->user('role') == "admin"){
                $project_role = "admin";
            }
            // if the user is not a admin and not a member
            elseif($project_role == ""){
                $project_role = "notmember";
            }


            $this->request->session()->write('selected_project_role', $project_role);
            $this->request->session()->write('selected_project_memberid', $project_memberid);
            // if the user is not a member of the project he can not access it
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
