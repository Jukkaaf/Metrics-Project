<?php
namespace App\Model\Table;

use App\Model\Entity\Metric;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MetricsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('metrics');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Metrictypes', [
            'foreignKey' => 'metrictype_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Weeklyreports', [
            'foreignKey' => 'weeklyreport_id',
            'joinType' => 'LEFT'
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
            ->add('value', 'valid', ['rule' => 'numeric'])
            ->requirePresence('value', 'create')
            ->notEmpty('value');
        
        $validator
            ->allowEmpty('weeklyreport_id');
        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['project_id'], 'Projects'));
        $rules->add($rules->existsIn(['metrictype_id'], 'Metrictypes'));
        return $rules;
    }
}
