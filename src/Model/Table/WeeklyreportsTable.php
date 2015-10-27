<?php
namespace App\Model\Table;

use App\Model\Entity\Weeklyreport;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\ORM\TableRegistry;
/**
 * Weeklyreports Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Projects
 */
class WeeklyreportsTable extends Table
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

        $this->table('weeklyreports');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
            'joinType' => 'INNER'
        ]);
    }
    
    // parsing trough the txt files contents to get the information out 
    public function saveUploadedReport($file_content){
        print_r($file_content);

        
        /*
        $Projects = TableRegistry::get('Projects');
        $query = $Projects
                ->find()
                ->select(['id'])
                ->where(['project_name' => "name from report"])
                ->toArray();
        
        print_r($query[0]->id);
        */
        
        // return an array with
        // project_id, int
        // title, string
        // date, Array ( [year] => 2015 [month] => 10 [day] => 24 ) 
        // reqlink, string
        // problems, string
        // meetings, string
        // additional, string
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
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->add('date', 'valid', ['rule' => 'date'])
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        $validator
            ->allowEmpty('reqlink');

        $validator
            ->allowEmpty('problems');

        $validator
            ->requirePresence('meetings', 'create')
            ->notEmpty('meetings');

        $validator
            ->allowEmpty('additional');

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
        $rules->add($rules->existsIn(['project_id'], 'Projects'));
        return $rules;
    }
}
