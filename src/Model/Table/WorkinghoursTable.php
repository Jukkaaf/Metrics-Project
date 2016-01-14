<?php
namespace App\Model\Table;

use App\Model\Entity\Workinghour;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class WorkinghoursTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('workinghours');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Members', [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Weeklyreports', [
            'foreignKey' => 'weeklyreport_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Worktypes', [
            'foreignKey' => 'worktype_id',
            'joinType' => 'INNER'
        ]);
    }
    
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('date', 'valid', ['rule' => 'date'])
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

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

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['member_id'], 'Members'));
        $rules->add($rules->existsIn(['worktype_id'], 'Worktypes'));
        return $rules;
    }
}
