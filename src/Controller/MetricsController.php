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

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $this->paginate = [
            'contain' => ['Projects', 'Metrictypes', 'Weeklyreports'],
            'conditions' => array('Metrics.project_id' => $project_id),
            'order' => ['date' => 'DESC']
        ];
        $this->set('metrics', $this->paginate($this->Metrics));
        $this->set('_serialize', ['metrics']);
    }
    

    /**
     * View method
     *
     * @param string|null $id Metric id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->get($id, [
            'contain' => ['Projects', 'Metrictypes', 'Weeklyreports'],
            'conditions' => array('Metrics.project_id' => $project_id)
        ]);
        $this->set('metric', $metric);
        $this->set('_serialize', ['metric']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
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

    public function addmultiple(){        
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->newEntity();
        
        if ($this->request->is('post')) {
            // the last key in the data is "submit", the value tells what button the user pressed 
            $formdata = $this->request->data;
            
            $entities = array();
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
                $temp['value'] = $formdata[$key];
                
                $entities[] = $temp;
                
                $metrictype += 1;
            }
            
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
    
    // probably bad/outdated code, update to be similar to the addmultiple in weeklyhours
    public function addmultiple_old(){        
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->newEntity();
        
        if ($this->request->is('post')) {
            $formdata = $this->Metrics->patchEntity($metric, $this->request->data);
            
            $keys = ["phase", "totalPhases", "reqNew", "reqInProgress", "reqClosed", "reqRejected", "commits", "passedTestCases", "totalTestCases"];
            // the project in this session
            $selected_project = $this->request->session()->read('selected_project');
            $current_weeklyreport = $this->request->session()->read('current_weeklyreport');
            
            // rolling counter for the metrictype
            // $keys array must be in same order as the metrictypes are in the database
            $metrictype = 1;
            $saveSuccess = True;
            
            // go trough the data from the form and read the data with keys from $keys array
            foreach($keys as $key){
                if($saveSuccess){
                    $metric = $this->Metrics->newEntity();
                    
                    $metric['project_id'] = $selected_project['id'];                      
                    $metric['metrictype_id'] = $metrictype;
                    $metrictype += 1;
                    $metric['weeklyreport_id'] = $current_weeklyreport['id'];
                    $metric['date'] = $current_weeklyreport['created_on'];
                    $metric['value'] = $formdata[$key];

                    if (!$this->Metrics->save($metric)){
                        $saveSuccess = False;
                    }
                    
                }  
            }
            if($saveSuccess){
                $this->Flash->success(__('The metrics were saved.'));
                return $this->redirect(
                    ['controller' => 'Weeklyhours', 'action' => 'addtest']
                );   
            }
            else{
                $this->Flash->error(__('The metrics could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Metrics->Projects->find('list', ['limit' => 200]);
        $metrictypes = $this->Metrics->Metrictypes->find('list', ['limit' => 200]);
        $weeklyreports = $this->Metrics->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $this->set(compact('metric', 'projects', 'metrictypes', 'weeklyreports'));
        $this->set('_serialize', ['metric']);
    }
    /**
     * Edit method
     *
     * @param string|null $id Metric id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {    
        $project_id = $this->request->session()->read('selected_project')['id'];
        $metric = $this->Metrics->get($id, [
            'contain' => [],
            'conditions' => array('Metrics.project_id' => $project_id)
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $metric = $this->Metrics->patchEntity($metric, $this->request->data);
            
            $metric['project_id'] = $project_id;
            
            if ($this->Metrics->save($metric)) {
                $this->Flash->success(__('The metric has been saved.'));
                //return $this->redirect(['action' => 'index']);
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

    /**
     * Delete method
     *
     * @param string|null $id Metric id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $metric = $this->Metrics->get($id);
        if ($this->Metrics->delete($metric)) {
            $this->Flash->success(__('The metric has been deleted.'));
        } else {
            $this->Flash->error(__('The metric could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function isAuthorized($user)
    {   
        // Check that the parameter in the request(the id in the url)
        // belongs to the project that is currently selected.
        // This is done so that users cant jump between projects by altering the url
        /*
        if($this->request->pass != null){
            $query = $this->Metrics
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
        */
        
        return parent::isAuthorized($user);
    }
}
