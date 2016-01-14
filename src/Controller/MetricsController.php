<?php
namespace App\Controller;

use App\Controller\AppController;
/**
 * Metrics Controller
 *
 * @property \App\Model\Table\MetricsTable $Metrics
 */
class MetricsController extends AppController
{

    public function index()
    {
        // only load metrics that are from the current project
        // the metrics are loaded in descending order by date
        $project_id = $this->request->session()->read('selected_project')['id'];
        $this->paginate = [
            'contain' => ['Projects', 'Metrictypes', 'Weeklyreports'],
            'conditions' => array('Metrics.project_id' => $project_id),
            'order' => ['date' => 'DESC']
        ];
        $this->set('metrics', $this->paginate($this->Metrics));
        $this->set('_serialize', ['metrics']);
    }
    
    public function view($id = null)
    {
        // only allow viewing metrics that are in the current project
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->get($id, [
            'contain' => ['Projects', 'Metrictypes', 'Weeklyreports'],
            'conditions' => array('Metrics.project_id' => $project_id)
        ]);
        $this->set('metric', $metric);
        $this->set('_serialize', ['metric']);
    }

    public function add()
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->newEntity();
        if ($this->request->is('post')) {
            // data loaded from the form
            $metric = $this->Metrics->patchEntity($metric, $this->request->data);
            // metrics can only be added to the current project
            $metric['project_id'] = $project_id;
            // if metrics are added outside of the weeklyreport the id will be null
            $metric['weeklyreport_id'] = NULL;

            if ($this->Metrics->save($metric)) {
                $this->Flash->success(__('The metric has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The metric could not be saved. Please, try again.'));
            }
        }
        $metrictypes = $this->Metrics->Metrictypes->find('list', ['limit' => 200]);
        //$weeklyreports = $this->Metrics->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $this->set(compact('metric', 'projects', 'metrictypes', 'weeklyreports'));
        $this->set('_serialize', ['metric']);
    }
    
    // a admin only function
    // admin are allowed to add metrics to weeklyreports outside the weeklyreport form
    public function addadmin()
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->newEntity();
        if ($this->request->is('post')) {
            $metric = $this->Metrics->patchEntity($metric, $this->request->data);
            
            $metric['project_id'] = $project_id;

            if ($this->Metrics->save($metric)) {
                $this->Flash->success(__('The metric has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The metric could not be saved. Please, try again.'));
            }
        }
        $metrictypes = $this->Metrics->Metrictypes->find('list', ['limit' => 200]);
        $weeklyreports = $this->Metrics->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $this->set(compact('metric', 'projects', 'metrictypes', 'weeklyreports'));
        $this->set('_serialize', ['metric']);
    }
    
    // fuction for adding multiplme metrics at once
    // used in the weeklyreport form
    public function addmultiple()
    {        
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->newEntity();
        
        if ($this->request->is('post')) {
            // the last key in the form data is "submit", the value tells what button the user pressed 
            $formdata = $this->request->data;
            
            $entities = array();
            // these keys are the metric types that are added with this function
            $keys = ["phase", "totalPhases", "reqNew", "reqInProgress", "reqClosed", "reqRejected", "commits", "passedTestCases", "totalTestCases"];
            // the project in this session
            $selected_project = $this->request->session()->read('selected_project');
            // rolling counter for the metrictype
            // $keys array must be in same order as the metrictypes are in the database
            $metrictype = 1;
            $current_weeklyreport = $this->request->session()->read('current_weeklyreport');
            // go trough the data from the form and read the data with keys from $keys array
            foreach($keys as $key){
                $temp = array();
                $temp['project_id'] = $selected_project['id'];                      
                $temp['metrictype_id'] = $metrictype;
                // the id does not exist yet
                //$temp['weeklyreport_id'] = $current_weeklyreport['id'];
                $temp['date'] = $current_weeklyreport['created_on'];
                // the value is loaded from the form data with the keys
                $temp['value'] = $formdata[$key];
                
                $entities[] = $temp;
                
                $metrictype += 1;
            }
            // create metrics entities of all the entities
            $metrics = $this->Metrics->newEntities($entities);
            // look for errors
            $dataok = True;
            foreach($metrics as $temp){
                if($temp->errors()){
                    $dataok = False;
                }
            }
            
            if($dataok){
                $this->request->session()->write('current_metrics', $metrics);
                // based on the last form data we either move back or forward in the form
                if($this->request->data['submit'] == "next"){
                    return $this->redirect(
                        ['controller' => 'Weeklyhours', 'action' => 'addmultiple']
                    );
                }
                else{
                    return $this->redirect(
                        ['controller' => 'Weeklyreports', 'action' => 'add']
                    );
                }
                  
            }
            else{
                $this->Flash->success(__('Metrics failed validation'));
            }
        }
        $projects = $this->Metrics->Projects->find('list', ['limit' => 200]);
        $metrictypes = $this->Metrics->Metrictypes->find('list', ['limit' => 200]);
        $weeklyreports = $this->Metrics->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $this->set(compact('metric', 'projects', 'metrictypes', 'weeklyreports'));
        $this->set('_serialize', ['metric']);
    }
    
    public function edit($id = null)
    {   
        // the metric can only be edited if its from the current project
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->get($id, [
            'contain' => [],
            'conditions' => array('Metrics.project_id' => $project_id)
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            // data from the form
            $metric = $this->Metrics->patchEntity($metric, $this->request->data);
            // it is made sure that the metric stays in the same project
            $metric['project_id'] = $project_id;
            
            if ($this->Metrics->save($metric)) {
                $this->Flash->success(__('The metric has been saved.'));
                // place the user back where they presed the edit button
                echo "<script>
                        window.history.go(-2);
                </script>";
            } else {
                $this->Flash->error(__('The metric could not be saved. Please, try again.'));
            }
        }
        $metrictypes = $this->Metrics->Metrictypes->find('list', ['limit' => 200]);
        $weeklyreports = $this->Metrics->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $this->set(compact('metric', 'projects', 'metrictypes', 'weeklyreports'));
        $this->set('_serialize', ['metric']);
    }

    // normal delete function
    // will not allow deletion of metrics that belong to weeklyreports
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $metric = $this->Metrics->get($id);

        if($metric['weeklyreport_id'] == NULL){
            if ($this->Metrics->delete($metric)) {
                $this->Flash->success(__('The metric has been deleted.'));
            }
            else {
                $this->Flash->error(__('The metric could not be deleted. Please, try again.'));
            } 
        }
        else {
            $this->Flash->error(__('Cannot delete metrics that belong to a weeklyreport'));
        } 
        return $this->redirect(['action' => 'index']);
        
    }
    
    // a admin only delete function, will allow deleting metrics that belong to weeklyreports
    public function deleteadmin($id = null)
    {
        $this->request->allowMethod(['post', 'deleteadmin']);
        $metric = $this->Metrics->get($id);

        if ($this->Metrics->delete($metric)) {
            $this->Flash->success(__('The metric has been deleted.'));
        }
        else {
            $this->Flash->error(__('The metric could not be deleted. Please, try again.'));
        } 

        return $this->redirect(['action' => 'index']);
        
    }
    
    public function isAuthorized($user)
    {   
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }
        
        
        return parent::isAuthorized($user);
    }
}
