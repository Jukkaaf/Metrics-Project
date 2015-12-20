<?php
namespace App\Controller;

use App\Controller\AppController;
/**
 * Members Controller
 *
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
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
            'contain' => ['Users', 'Projects'],
            'conditions' => array('Members.project_id' => $project_id)
        ];
        $this->set('members', $this->paginate($this->Members));
        $this->set('_serialize', ['members']);
        
        //print_r($this->request->session()->read('selected_project_role'));
    }

    /**
     * View method
     *
     * @param string|null $id Member id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $member = $this->Members->get($id, [
            'contain' => ['Users', 'Projects', 'Workinghours', 'Weeklyhours'],
            'conditions' => array('Members.project_id' => $project_id)
        ]);
        $this->set('member', $member);
        $this->set('_serialize', ['member']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $member = $this->Members->newEntity();
        if ($this->request->is('post')) {
            $member = $this->Members->patchEntity($member, $this->request->data);
            
            $member['project_id'] = $project_id;
            
            // mangers cannot add supervisors
            if($member['project_role'] != "supervisor" || $this->request->session()->read('selected_project_role') != 'manager'){
                if ($this->Members->save($member)) {
                    $this->Flash->success(__('The member has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The member could not be saved. Please, try again.'));
                }
            }
            else{
                $this->Flash->error(__('Managers cannot add supervisors'));
            }
        }          
        $users = $this->Members->Users->find('list', ['limit' => 200, 'conditions'=>array('Users.role !=' => 'inactive')]);
        $this->set(compact('member', 'users', 'projects'));
        $this->set('_serialize', ['member']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Member id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $member = $this->Members->get($id, [
            'contain' => [],
            'conditions' => array('Members.project_id' => $project_id)
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $member = $this->Members->patchEntity($member, $this->request->data);
            
            $member['project_id'] = $project_id;

            // mangers cannot edit supervisors
            if($member['project_role'] != "supervisor" || $this->request->session()->read('selected_project_role') != 'manager'){
                if ($this->Members->save($member)) {
                    $this->Flash->success(__('The member has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The member could not be saved. Please, try again.'));
                }
            }
            else{
                $this->Flash->error(__('Managers cannot edit supervisors'));
            }
        }
        $users = $this->Members->Users->find('list', ['limit' => 200, 'conditions'=>array('Users.role !=' => 'inactive')]);
        $this->set(compact('member', 'users', 'projects'));
        $this->set('_serialize', ['member']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Member id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $member = $this->Members->get($id);
        // managers cannot delete supervisors
        if($member['project_role'] != "supervisor" || $this->request->session()->read('selected_project_role') != 'manager'){
            if ($this->Members->delete($member)) {
                $this->Flash->success(__('The member has been deleted.'));
            } else {
                $this->Flash->error(__('The member could not be deleted. Please, try again.'));
            }
        }
        else{
            $this->Flash->error(__('Managers cannot delete supervisors'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    
    public function isAuthorized($user)
    {   
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }
        
        $project_role = $this->request->session()->read('selected_project_role');
        
        //special rule for members controller.
        //only supervisors admins can edit and delete members
        // managers can add members, not supervisors
        if ($this->request->action === 'add') 
        {
            if($project_role == "manager"){
                return True;
            }
        }
        
        if ($this->request->action === 'add' || $this->request->action === 'edit'
            || $this->request->action === 'delete') 
        {
            if($project_role == "supervisor"){
                return True;
            }

            // This return false is important, because if we didnt have it a manager could also
            // add edit and delete members. This is because after this if block we call the parent
            return False;
        }
        // if not trying to add edit delete
        return parent::isAuthorized($user);
    }
}
