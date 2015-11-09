<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\Folder;

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
        
        $this->request->session()->write('selected_project', $project);
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
            
            // create a folder for the project
            $path = ROOT . DS . 'reports' . DS . $this->request->data['project_name'];
            $dir = new Folder($path, true, 0755);

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
    
    public function isAuthorized($user)
    {      
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // All registered users can add go to the index page
        if ($this->request->action === 'index' || $this->request->action === 'view') {
            return true;
        }
        
        //return parent::isAuthorized($user);
        
        // Default deny
        return false;
    }
}
