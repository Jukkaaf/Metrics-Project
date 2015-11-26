<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
/**
 * Weeklyhours Controller
 *
 * @property \App\Model\Table\WeeklyhoursTable $Weeklyhours
 */
class WeeklyhoursController extends AppController
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
            'contain' => ['Weeklyreports', 'Members'],
            'conditions' => array('Members.project_id' => $project_id)
        ];
        $this->set('weeklyhours', $this->paginate($this->Weeklyhours));
        $this->set('_serialize', ['weeklyhours']);
    }

    /**
     * View method
     *
     * @param string|null $id Weeklyhour id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyhour = $this->Weeklyhours->get($id, [
            'contain' => ['Weeklyreports', 'Members'],
            'conditions' => array('Members.project_id' => $project_id)
        ]);
        $this->set('weeklyhour', $weeklyhour);
        $this->set('_serialize', ['weeklyhour']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $weeklyhour = $this->Weeklyhours->newEntity();
        if ($this->request->is('post')) {
            $weeklyhour = $this->Weeklyhours->patchEntity($weeklyhour, $this->request->data);
            if ($this->Weeklyhours->save($weeklyhour)) {
                $this->Flash->success(__('The weeklyhour has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weeklyhour could not be saved. Please, try again.'));
            }
        }
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyreports = $this->Weeklyhours->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $now = Time::now();
        $members = $this->Weeklyhours->Members->find('list', ['limit' => 200])
                                                ->where(['Members.project_id' => $project_id, 'Members.project_role !=' => 'supervisor', 'Members.ending_date >' => $now])
                                                ->orWhere(['Members.project_id' => $project_id, 'Members.project_role !=' => 'supervisor', 'Members.ending_date IS' => NULL]);
        $this->set(compact('weeklyhour', 'weeklyreports', 'members'));
        $this->set('_serialize', ['weeklyhour']);
    }
    
    public function addmultiple()
    {   
        $project_id = $this->request->session()->read('selected_project')['id'];
        $current_weeklyreport = $this->request->session()->read('current_weeklyreport');
        // create a list of key valuepairs where thevalue is their member id and key is the members email + role
        $memberlist = $this->Weeklyhours->getMembers($project_id);
        //count the workinghours for members so they can be translated to weeklyhours 
        $hourlist = $this->Weeklyhours->getHours($memberlist, $current_weeklyreport['week']);
        
        $weeklyhours = $this->Weeklyhours->newEntity();
        if ($this->request->is('post')) {
            // the last key in the data is "submit", the value tells what button the user pressed 
            $formdata = $this->request->data;
            $entities = array();

            for($count = 0; $count < count($memberlist); $count++){
                $temp = array();
                // the id does not exist yet
                //$temp['weeklyreport_id'] = $current_weeklyreport['id'];
                $temp['member_id'] = $memberlist[$count]['id'];
                $temp['duration'] = $formdata[$count]['duration'];
                
                $entities[] = $temp;
            }
            
            $weeklyhours = $this->Weeklyhours->newEntities($entities);
            
            $dataok = True;
            foreach($weeklyhours as $temp){
                if($temp->errors()){
                    $dataok = False;
                }
            }
            
            if($dataok){
                $this->request->session()->write('current_weeklyhours', $weeklyhours);
                if($this->request->data['submit'] == "submit"){
                    
                    // save all the parts of the weeklyreport that are saved in the session
                    $current_metrics = $this->request->session()->read('current_metrics');
                    
                    if($this->Weeklyhours->saveSessionReport($current_weeklyreport, $current_metrics, $weeklyhours)){
                        $this->Flash->success(__('Weeklyreport saved'));
                        
                        $this->request->session()->delete('current_weeklyreport');
                        $this->request->session()->delete('current_metrics');
                        $this->request->session()->delete('current_weeklyhours');
                        
                        return $this->redirect(
                            ['controller' => 'Weeklyreports', 'action' => 'index']
                        );
                    }
                    else{
                        $this->Flash->success(__('Saving weeklyreport failed'));
                    }
                    
                }
                else{
                    return $this->redirect(
                        ['controller' => 'Metrics', 'action' => 'addmultiple']
                    ); 
                }
                  
            }
            else{
                $this->Flash->success(__('Weeklyhours failed validation'));
            }
        }
        $weeklyreports = $this->Weeklyhours->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $members = $this->Weeklyhours->Members->find('list', ['limit' => 200, 'conditions' => array('Members.project_id' => $project_id)]);
        $this->set(compact('weeklyhours', 'weeklyreports', 'members', 'memberlist', 'hourlist'));
        $this->set('_serialize', ['weeklyhour']);
    }
    
    public function addmultiple_temp()
    {
        // here just because of weeklyhours.ctp, look in to removing
        $weeklyhour = $this->Weeklyhours->newEntity();
        
        if ($this->request->is('post')) {
            // because this form has multiple weeklyhours we cannot just put the data in to an entity
            // here we just take all the data from the form
            $current_weeklyreport = $this->request->session()->read('current_weeklyreport');
            $formdata = $this->request->data;
            $weeklyhours = $this->Weeklyhours->formatData($formdata, $current_weeklyreport);
            $duplicates = $this->Weeklyhours->duplicates($weeklyhours);
    
            if(!$duplicates){    
                // newEntities makes all the objects in the array to their own entities
                $entities = $this->Weeklyhours->newEntities($weeklyhours);       
                $saving_ok = True;
                // saving all the entities one by one
                foreach($entities as $ent){
                    if (!$this->Weeklyhours->save($ent)) {
                        $this->Flash->error(__('The weeklyhours could not be saved. Please, try again.'));
                        $saving_ok = False;
                    } 
                }

                if($saving_ok){
                    $this->Flash->success(__('The weeklyhours have been saved. Weeklyreport complete.'));
                    $this->request->session()->delete('current_weeklyreport');
                    return $this->redirect(['controller' => 'Weeklyreports', 'action' => 'index']);  
                }
            }
            else{
                $this->Flash->error(__('The weeklyhours could not be saved. Duplicate members'));
            }
        }
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyreports = $this->Weeklyhours->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $members = $this->Weeklyhours->Members->find('list', ['limit' => 200, 'conditions' => array('Members.project_id' => $project_id)]);
        $this->set(compact('weeklyhour', 'weeklyreports', 'members'));
        $this->set('_serialize', ['weeklyhour']);
    }
    /**
     * Edit method
     *
     * @param string|null $id Weeklyhour id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $project_id = $this->request->session()->read('selected_project')['id'];
        $weeklyhour = $this->Weeklyhours->get($id, [
            'contain' => ['Members'],
            'conditions' => array('Members.project_id' => $project_id)
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyhour = $this->Weeklyhours->patchEntity($weeklyhour, $this->request->data);
            // can edit without changing weeklyreport id
            if ($this->Weeklyhours->save($weeklyhour)) {
                $this->Flash->success(__('The weeklyhour has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weeklyhour could not be saved. Please, try again.'));
            }
        }
        $weeklyreports = $this->Weeklyhours->Weeklyreports->find('list', ['limit' => 200, 'conditions' => array('Weeklyreports.project_id' => $project_id)]);
        $now = Time::now();
        $members = $this->Weeklyhours->Members->find('list', ['limit' => 200])
                                                ->where(['Members.project_id' => $project_id, 'Members.project_role !=' => 'supervisor', 'Members.ending_date >' => $now])
                                                ->orWhere(['Members.project_id' => $project_id, 'Members.project_role !=' => 'supervisor', 'Members.ending_date IS' => NULL]);
        $this->set(compact('weeklyhour', 'weeklyreports', 'members'));
        $this->set('_serialize', ['weeklyhour']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Weeklyhour id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $weeklyhour = $this->Weeklyhours->get($id);
        if ($this->Weeklyhours->delete($weeklyhour)) {
            $this->Flash->success(__('The weeklyhour has been deleted.'));
        } else {
            $this->Flash->error(__('The weeklyhour could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
