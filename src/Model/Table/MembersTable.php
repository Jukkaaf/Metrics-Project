<?php
namespace App\Model\Table;

use App\Model\Entity\Member;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class MembersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('members');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Workinghours', [
            'foreignKey' => 'member_id'
        ]);
        $this->hasMany('Weeklyhours', [
            'foreignKey' => 'member_id'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('project_role', 'create')
            ->notEmpty('project_role')
            ->add('project_role', 'inList', [
                'rule' => ['inList', ['developer', 'manager', 'supervisor']],
                'message' => 'Please enter a valid project role'
                ]);
        
        $validator
            ->add('starting_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('starting_date');

        $validator
            ->add('ending_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('ending_date');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['project_id'], 'Projects'));
        return $rules;
    }
    
    public function getMembers($project_id){
        // returns an array with project members
        // the info is the members id, project role and email from user
        $memberinfo = array();
        $now = Time::now();
        $members = TableRegistry::get('Members');   
        $query = $members
            ->find()
            ->select(['id', 'project_role', 'user_id'])
            ->where(['project_id' => $project_id, 'project_role !=' => 'supervisor', 'ending_date >' => $now])
            ->orWhere(['project_id' => $project_id, 'project_role !=' => 'supervisor', 'ending_date IS' => NULL])
            ->toArray();
        
        $users = TableRegistry::get('Users'); 
        foreach($query as $temp){         
            $query2 = $users
                ->find()
                ->select(['role', 'first_name', 'last_name'])
                ->where(['id =' => $temp->user_id])
                ->toArray();
            
            $temp_memberinfo['id'] = $temp->id;
            $temp_memberinfo['member_name'] = $query2[0]->first_name." ".$query2[0]->last_name." - ".$temp->project_role; 

            $memberinfo[] = $temp_memberinfo; 
        }
        
        return $memberinfo;
    }
}
