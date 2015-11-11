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
        $member = $this->Members->get($id, [
            'contain' => ['Users', 'Projects', 'Workinghours']
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

            if ($this->Members->save($member)) {
                $this->Flash->success(__('The member has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The member could not be saved. Please, try again.'));
            }
        }          
        $users = $this->Members->Users->find('list', ['limit' => 200]);
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
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $member = $this->Members->patchEntity($member, $this->request->data);
            
            $member['project_id'] = $project_id;

            if ($this->Members->save($member)) {
                $this->Flash->success(__('The member has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The member could not be saved. Please, try again.'));
            }
        }
        $users = $this->Members->Users->find('list', ['limit' => 200]);
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
        if ($this->Members->delete($member)) {
            $this->Flash->success(__('The member has been deleted.'));
        } else {
            $this->Flash->error(__('The member could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    
    public function isAuthorized($user)
    {   
        // Check that the parameter in the request(the id in the url)
        // belongs to the project that is currently selected.
        // This is done so that users cant jump between projects by altering the url
        if($this->request->pass != null){
            $query = $this->Members
                ->find()
                ->select(['project_id'])
                ->where(['id =' => $this->request->pass[0]])
                ->toArray();
            
            $project_id = $this->request->session()->read('selected_project')['id'];
            
            // does the project_id of the the object the parameter points to
            if($query[0]->project_id != $project_id){
                return False;
            }
        }

        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }
        
        $project_role = $this->request->session()->read('selected_project_role');
        
        //special rule for members controller.
        //only supervisors admins can add edit and delete members
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
