<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class WeeklyreportsController extends AppController
{
    public function initialize()
    {
        parent::initialize();    
    }
    
    public function index()
    {
        // only load weeklyreports from the current project
        // they are in order by year and week
        $project_id = $this->request->session()->read('selected_project')['id'];
        $this->paginate = [
            'contain' => ['Projects'],
            'conditions' => array('Weeklyreports.project_id' => $project_id),
            'order' => ['year' => 'DESC', 'week' => 'DESC']
        ];
        $this->set('weeklyreports', $this->paginate($this->Weeklyreports));
        $this->set('_serialize', ['weeklyreports']);
    }

    public function view($id = null)
    {
        // only load if the report is from the current project
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyreport = $this->Weeklyreports->get($id, [
            'contain' => ['Projects', 'Metrics', 'Weeklyhours'],
            'conditions' => array('Weeklyreports.project_id' => $project_id)
        ]);
        
        // get members because the weeklyhours table has a function we want to use
        $members = TableRegistry::get('Members');
        // list of members so we can display usernames instead of id's
        $memberlist = $members->getMembers($project_id);
        foreach($weeklyreport->weeklyhours as $weeklyhours){
            foreach($memberlist as $member){
                // if the id's match add the correct name
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
                // if the id's match add the correct description
                if($metrics->metrictype_id == $metrictypes->id){
                   $metrics['metric_description'] = $metrictypes->description;
                }
            }
        }
        $this->set('weeklyreport', $weeklyreport);
        $this->set('_serialize', ['weeklyreport']);
    }

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
    
    public function edit($id = null)
    {
        // only allow editing id the weeklyreport is from the current project
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyreport = $this->Weeklyreports->get($id, [
            'contain' => [],
            'conditions' => array('Weeklyreports.project_id' => $project_id)
        ]);
        
        $old_weeknumber = $weeklyreport['week'];
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyreport = $this->Weeklyreports->patchEntity($weeklyreport, $this->request->data);
            
            // project id cannot be changed, and its made sure it does not change
            $weeklyreport['project_id'] = $project_id;
            // updated_on date is automatic
            $weeklyreport['updated_on'] = Time::now();
            
            // check that this week does not already have a weeklyreport.
            // but allow updating withouth changing the week number
            // checkUnique is in "WeeklyreportsTable.php"
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
