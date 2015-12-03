<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
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
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyreport = $this->Weeklyreports->get($id, [
            'contain' => ['Projects', 'Metrics', 'Weeklyhours'],
            'conditions' => array('Weeklyreports.project_id' => $project_id)
        ]);
        
        // get weeklyhours because the weeklyhours table has a function we want to use
        $weeklyhours = TableRegistry::get('Weeklyhours');
        // list of members so we can display usernames instead of id's
        $memberlist = $weeklyhours->getMembers($project_id);
        foreach($weeklyreport->weeklyhours as $weeklyhours){
            foreach($memberlist as $member){
                if($weeklyhours->member_id == $member['id']){
                   $weeklyhours['member_name'] = $member['member_name'];
                }
            }
        }
        // get descriptions for the metrics
        $metrictypes = TableRegistry::get('Metrictypes');
        $query = $metrictypes
            ->find()
            ->select(['id','description'])
            ->toArray();
        foreach($weeklyreport->metrics as $metrics){
            foreach($query as $metrictypes){
                if($metrics->metrictype_id == $metrictypes->id){
                   $metrics['metric_description'] = $metrictypes->description;
                }
            }
        }
        
        
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
            // read the form data and edit it
            $report = $this->request->data;  
            $report['project_id'] = $project_id;
            $report['created_on'] = Time::now();
            // validate the data and apply it to the weeklyreport object
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $report);
            
            // if the object validated correctly and it is unique we can save it in the session
            // and move on to the next page
            if($this->Weeklyreports->checkUnique($weeklyreport)){  
                if(!$weeklyreport->errors()){
                    $this->request->session()->write('current_weeklyreport', $weeklyreport);
                    return $this->redirect(
                        ['controller' => 'Metrics', 'action' => 'addmultiple']
                    ); 
                }
                else {
                    $this->Flash->error(__('Report failed validation'));
                }
            }
            else {
                $this->Flash->error(__('This week already has a weeklyreport'));
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
            'contain' => [],
            'conditions' => array('Weeklyreports.project_id' => $project_id)
        ]);
        
        $old_weeknumber = $weeklyreport['week'];
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $this->request->data);
            
            $weeklyreport['project_id'] = $project_id;
            $weeklyreport['updated_on'] = Time::now();
            
            // check that this week does not already have a weeklyreport.
            // but allow updating withouth changing the week number
            if($this->Weeklyreports->checkUnique($weeklyreport) || $old_weeknumber == $weeklyreport['week']){
                if ($this->Weeklyreports->save($weeklyreport)) {
                    $this->Flash->success(__('The weeklyreport has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The weeklyreport could not be saved. Please, try again.'));
                }
            }
            else {
                $this->Flash->error(__('This week already has a weeklyreport'));
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
}
