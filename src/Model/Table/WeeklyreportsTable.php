<?php
namespace App\Model\Table;

use App\Model\Entity\Weeklyreport;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Filesystem\File;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class WeeklyreportsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('weeklyreports');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Metrics', [
            'foreignKey' => 'weeklyreport_id'
        ]);
        $this->hasMany('Workinghours', [
            'foreignKey' => 'member_id'
        ]);
        $this->hasMany('Weeklyhours', [
            'foreignKey' => 'weeklyreport_id'
        ]);
    }
    
    // check if the project_id and week pair already exists
    public function checkUnique($report){   
        $weeklyreports = TableRegistry::get('Weeklyreports');
        $query = $weeklyreports
                ->find()
                ->select(['project_id', 'week'])
                ->where(['project_id =' => $report['project_id']])
                ->where(['year =' => $report['year']])
                ->where(['week =' => $report['week']]);
                
        foreach($query as $temp){
            if($temp['project_id'] == $report['project_id']){
                return False;
            }
        }
        return True;
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->add('week', 'valid', [
                'rule' => 'numeric',
                // this is the weeknumber range
                // maximum weeknumber is 53
                'rule' => ['range', 1, 53]
                ])
            ->requirePresence('week', 'create')
            ->notEmpty('week');
        
        $validator
            ->add('year', 'valid', [
                'rule' => 'numeric',
                // this is the weeknumber range
                // maximum weeknumber is 53
                'rule' => ['range', 2000, 2100]
                ])
            ->requirePresence('year', 'create')
            ->notEmpty('year');

        $validator
            ->allowEmpty('reglink');

        $validator
            ->allowEmpty('problems');

        $validator
            ->requirePresence('meetings', 'create')
            ->notEmpty('meetings');

        $validator
            ->allowEmpty('additional');
        
        $validator
            ->add('created_on', 'valid', ['rule' => 'date'])
            ->requirePresence('created_on', 'create')
            ->notEmpty('created_on');

        $validator
            ->add('updated_on', 'valid', ['rule' => 'date'])
            ->allowEmpty('updated_on');
        
        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['project_id'], 'Projects'));
        $rules->add($rules->isUnique(['week', 'year', 'project_id']));
        return $rules;
    }
}
