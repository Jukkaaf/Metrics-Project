<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $this->paginate = [
            'contain' => ['Projects']
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
        $weeklyreport = $this->Weeklyreports->newEntity();
        if ($this->request->is('post')) {
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $this->request->data);
            
            // add the session project_id to the report
            if($this->request->session()->check('selected_project')){
                $selected_project = $this->request->session()->read('selected_project');
                $weeklyreport['project_id'] = $selected_project['id'];
            }
            
            if ($this->Weeklyreports->save($weeklyreport)) {
                $this->Flash->success(__('The weeklyreport has been saved.'));
                
                // check if the report was made by uploading a report file
                // if so, we should have a variable stored in the session with the contents of the file
                if($this->request->session()->check('report')){
                    $this->addUploaded();
                }
                else{
                    return $this->redirect(['action' => 'index']);
                }             
            } else {
                $this->Flash->error(__('The weeklyreport could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Weeklyreports->Projects->find('list', ['limit' => 200]);
        $this->set(compact('weeklyreport', 'projects'));
        $this->set('_serialize', ['weeklyreport']);    
    }
    
    private function addUploaded(){
        $report = $this->request->session()->read('report');
        // save the report on the server harddrive
        $this->Weeklyreports->saveUploadedReport($report["file_content"]);

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
        $weeklyreport = $this->Weeklyreports->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $this->request->data);
            
            // add the session project_id to the report
            if($this->request->session()->check('selected_project')){
                $selected_project = $this->request->session()->read('selected_project');
                $weeklyreport['project_id'] = $selected_project['id'];
            }
            
            if ($this->Weeklyreports->save($weeklyreport)) {
                $this->Flash->success(__('The weeklyreport has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weeklyreport could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Weeklyreports->Projects->find('list', ['limit' => 200]);
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
}
