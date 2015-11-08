<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $this->paginate = [
            'contain' => ['Weeklyreports', 'Members']
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
        $weeklyhour = $this->Weeklyhours->get($id, [
            'contain' => ['Weeklyreports', 'Members']
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
        $weeklyreports = $this->Weeklyhours->Weeklyreports->find('list', ['limit' => 200]);
        $members = $this->Weeklyhours->Members->find('list', ['limit' => 200]);
        $this->set(compact('weeklyhour', 'weeklyreports', 'members'));
        $this->set('_serialize', ['weeklyhour']);
    }
    
    
    public function addmultiple()
    {
        // here just because of weeklyhours.ctp, look in to removing
        $weeklyhour = $this->Weeklyhours->newEntity();
        
        if ($this->request->is('post')) {
            // because this form has multiple weeklyhours we cannot just put the data in to an entity
            // here we just take all the data from the form
            $formdata = $this->request->data;
            // keys from the form, for going trough the key value pairs
            $keys = array_keys($formdata);
            $current_weeklyreport = $this->request->session()->read('current_weeklyreport');
            $weeklyhours = array();
            
            // in this for loop we format the data correctly and insert the weeklyreport_id
            for($count = 0; $count < count($formdata); $count++){
                $temp = $formdata[$keys[$count]];
                $temp['weeklyreport_id'] = $current_weeklyreport['id']; 
                $weeklyhours[] = $temp;
            }
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
        $weeklyreports = $this->Weeklyhours->Weeklyreports->find('list', ['limit' => 200]);
        $members = $this->Weeklyhours->Members->find('list', ['limit' => 200]);
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
        $weeklyhour = $this->Weeklyhours->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyhour = $this->Weeklyhours->patchEntity($weeklyhour, $this->request->data);
            if ($this->Weeklyhours->save($weeklyhour)) {
                $this->Flash->success(__('The weeklyhour has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weeklyhour could not be saved. Please, try again.'));
            }
        }
        $weeklyreports = $this->Weeklyhours->Weeklyreports->find('list', ['limit' => 200]);
        $members = $this->Weeklyhours->Members->find('list', ['limit' => 200]);
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
