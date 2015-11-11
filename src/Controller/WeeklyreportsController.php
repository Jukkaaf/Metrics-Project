<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
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
            $report = [];
            $file_content = $this->Upload->send($this->request->data['uploadfile']);
            if($file_content != null){
                // read the file in to a key value array
                $report = $this->Weeklyreports->parseReport($file_content);
                // save the report in the session
                // the file contents are also stored in the report variable
                $this->request->session()->write('report', $report);
                
                return $this->redirect(
                    ['controller' => 'Weeklyreports', 'action' => 'add']
                );
            }
        }
    }
    
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $this->paginate = [
            'contain' => ['Projects'],
            'conditions' => array('Weeklyreports.project_id' => $project_id)
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
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyreport = $this->Weeklyreports->newEntity();
        if ($this->request->is('post')) {
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $this->request->data);
            
            $weeklyreport['project_id'] = $project_id;
            $weeklyreport['created_on'] = Time::now();
            
            if ($this->Weeklyreports->save($weeklyreport)) {
                $this->Flash->success(__('The first part saved'));
                
                // save the current weeklyreport in the session so it can be used on the next page on the form
                $this->request->session()->write('current_weeklyreport', $weeklyreport);
                
                return $this->redirect(
                    ['controller' => 'Metrics', 'action' => 'addmultiple']
                );            
            } else {
                $this->Flash->error(__('The weeklyreport could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('weeklyreport', 'projects'));
        $this->set('_serialize', ['weeklyreport']);    
    }
    
    private function addUploaded(){
        $report = $this->request->session()->read('report');
        // save the report on the server harddrive
        $project_name = "temp";
        if($this->request->session()->check('selected_project')){
            $selected_project = $this->request->session()->read('selected_project');
            $project_name = $selected_project['project_name'];
        }
        $this->Weeklyreports->saveUploadedReport($report["file_content"], $project_name);

        // if the report included workinghours
        // we will move onwards to adding them
        if(array_key_exists('actual_hours', $this->request->session()->read('report'))){
            return $this->redirect(
                ['controller' => 'Workinghours', 'action' => 'add']
            );
        }
        else{
            $this->request->session()->delete('report');
        }
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
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyreport = $this->Weeklyreports->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $this->request->data);
            
            $weeklyreport['project_id'] = $project_id;
            $weeklyreport['updated_on'] = Time::now();
            
            if ($this->Weeklyreports->save($weeklyreport)) {
                $this->Flash->success(__('The weeklyreport has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weeklyreport could not be saved. Please, try again.'));
            }
        }
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
    
    public function isAuthorized($user)
    {   
        // Check that the parameter in the request(the id in the url)
        // belongs to the project that is currently selected.
        // This is done so that users cant jump between projects by altering the url
        if($this->request->pass != null){
            $query = $this->Weeklyreports
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
        
        
        return parent::isAuthorized($user);
    }
}
