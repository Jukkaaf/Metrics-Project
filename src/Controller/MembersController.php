<?php
namespace App\Controller;

use App\Controller\AppController;

class MembersController extends AppController
{
    
    public function index()
    {
        // Only members of the current project are loaded
        $project_id = $this->request->session()->read('selected_project')['id'];   
        $this->paginate = [
            'contain' => ['Users', 'Projects'],
            'conditions' => array('Members.project_id' => $project_id)
        ];
        $this->set('members', $this->paginate($this->Members));
        $this->set('_serialize', ['members']);

    }

    public function view($id = null)
    {
        // The member with the id "$id" is loaded
        // IF the member is a part of the currently selected project
        $project_id = $this->request->session()->read('selected_project')['id'];
        $member = $this->Members->get($id, [
            'contain' => ['Users', 'Projects', 'Workinghours', 'Weeklyhours'],
            'conditions' => array('Members.project_id' => $project_id)
        ]);
        $this->set('member', $member);
        $this->set('_serialize', ['member']);
    }

    public function add()
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $member = $this->Members->newEntity();
        
        if ($this->request->is('post')) {
            // data from the form is loaded in to the new member object
            $member = $this->Members->patchEntity($member, $this->request->data);
            // the member is made a part of the currently selected project
            $member['project_id'] = $project_id;
            
            // Managers are not allowed to add members that are supervisors
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

    public function edit($id = null)
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        // The selected member is only loaded if the member is a part of the curren project
        $member = $this->Members->get($id, [
            'contain' => [],
            'conditions' => array('Members.project_id' => $project_id)
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            // data is loaded from the form
            $member = $this->Members->patchEntity($member, $this->request->data);
            // it is made sure that the updated member stays in the current project
            $member['project_id'] = $project_id;

            if ($this->Members->save($member)) {
                $this->Flash->success(__('The member has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The member could not be saved. Please, try again.'));
            }
        }
        $users = $this->Members->Users->find('list', ['limit' => 200, 'conditions'=>array('Users.role !=' => 'inactive')]);
        $this->set(compact('member', 'users', 'projects'));
        $this->set('_serialize', ['member']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $member = $this->Members->get($id);
        if ($this->Members->delete($member)) {
            $this->Flash->success(__('The member has been deleted.'));
        } else {
            $this->Flash->error(__('The member could not be deleted. Please, try again.'));
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
        
        // special rules for members controller.
        
        // managers can add members, but cannot add new supervisors
        if ($this->request->action === 'add') 
        {
            if($project_role == "manager" || $project_role == "supervisor"){
                return True;
            }
        }
        // only supervisors and admins can edit and delete members
        if ($this->request->action === 'edit' || $this->request->action === 'delete') 
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
