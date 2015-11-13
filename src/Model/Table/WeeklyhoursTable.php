<?php
namespace App\Model\Table;

use App\Model\Entity\Weeklyhour;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
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
        // check if the project_id week pari already exists
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
                'rule' => ['range', 1, 168]
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
}
