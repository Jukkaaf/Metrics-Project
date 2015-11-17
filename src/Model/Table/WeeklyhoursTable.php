<?php
namespace App\Model\Table;

use App\Model\Entity\Weeklyhour;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
/**
 * Weeklyhours Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Weeklyreports
 * @property \Cake\ORM\Association\BelongsTo $Members
 */
class WeeklyhoursTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('weeklyhours');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Weeklyreports', [
            'foreignKey' => 'weeklyreport_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Members', [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER'
        ]);
    }
    
    public function checkUnique($hour){
        // check if the weeklyreport member_id pair already exists
        $weeklyhours = TableRegistry::get('Weeklyhours');
        $query = $weeklyhours
                ->find()
                ->select(['weeklyreport_id', 'member_id'])
                ->where(['weeklyreport_id =' => $hour['weeklyreport_id']])
                ->where(['member_id =' => $hour['member_id']]);
                
        foreach($query as $temp){
            if($temp['weeklyreport_id'] == $hour['weeklyreport_id']){
                return False;
            }
        }
        return True;
    }
    
    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('duration', 'valid', [
                'rule' => 'numeric',
                // minimum of 0 hours, max of 7 * 24
                'rule' => ['range', 0, 168]
                ])
            ->requirePresence('duration', 'create')
            ->notEmpty('duration');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['weeklyreport_id'], 'Weeklyreports'));
        $rules->add($rules->existsIn(['member_id'], 'Members'));
        return $rules;
    }
    
    public function formatData($formdata, $current_weeklyreport){
        // keys from the form, for going trough the key value pairs
        $keys = array_keys($formdata);
        $weeklyhours = array();

        // in this for loop we format the data correctly and insert the weeklyreport_id
        for($count = 0; $count < count($formdata); $count++){
            $temp = $formdata[$keys[$count]];
            $temp['weeklyreport_id'] = $current_weeklyreport['id']; 
            $weeklyhours[] = $temp;
        }
        return $weeklyhours;
    }
    
    public function duplicates($weeklyhours){
        $tempmembers = array();
        foreach($weeklyhours as $temp){
            // keep a list of all members to and make sure there is only one of each
            if(in_array($temp['member_id'], $tempmembers)){
                return True;
            }
            else{
                $tempmembers[] = $temp['member_id'];
            }
        }
        return False;
    }
    
    public function getMembers($project_id){
        // returns an array with members
        // the info is the members id, project role and email from user
        $memberinfo = array();
        
        $members = TableRegistry::get('Members');   
        $query = $members
            ->find()
            ->select(['id', 'project_role', 'user_id'])
            ->where(['project_id =' => $project_id])
            ->toArray();
        
        $users = TableRegistry::get('Users'); 
        foreach($query as $temp){         
            $query2 = $users
                ->find()
                ->select(['email'])
                ->where(['id =' => $temp->user_id])
                ->toArray();
            
            $temp_memberinfo['id'] = $temp->id;
            $temp_memberinfo['member_name'] = $query2[0]->email." - ".$temp->project_role; 
            //$temp_memberinfo['email'] = $query2[0]->email;
            
            $memberinfo[] = $temp_memberinfo;
            
            //$memberinfo[$query2[0]->email." ".$temp->project_role] = $temp->id;
            //$memberinfo[$temp->id] = $query2[0]->email." ".$temp->project_role; 
        }
        
        return $memberinfo;
    }
    
    public function getHours($memberlist, $week){
        $workinghours = TableRegistry::get('Workinghours');
        $hours = array();
        foreach($memberlist as $member){
            $temphour = 0;
            $query = $workinghours
               ->find()
               ->select(['duration', 'date'])
               ->where(['member_id =' => $member['id']])
               ->toArray();
            
            foreach($query as $temp){
                $time = new Time($temp['date']);
                if($time->weekOfYear == $week){
                    $temphour = $temphour + $temp['duration'];
                }
            }
            $hours[] = $temphour;
        } 
        return $hours;
    }
    
    public function saveSessionReport($weeklyreport, $metrics, $weeklyhours){
        $tableWeeklyreports = TableRegistry::get('Weeklyreports');
        
        if (!$tableWeeklyreports->save($weeklyreport)) {
            return False;
        }
        // now the weeklyreport has its own id, so we can insert it in to the metrics and weeklyhours
        
        $tableMetrics = TableRegistry::get('Metrics');
        foreach($metrics as $temp){
            $temp['weeklyreport_id'] = $weeklyreport['id'];
            
            if (!$tableMetrics->save($temp)) {
                return False;
            }
        }
        
        $tableWeeklyhours = TableRegistry::get('Weeklyhours');
        foreach($weeklyhours as $temp){
            $temp['weeklyreport_id'] = $weeklyreport['id'];
            
            if (!$tableWeeklyhours->save($temp)) {
                return False;
            }
        }
        
        
        return True;
    }
    
    
}
