<?php
namespace App\Model\Table;

use App\Model\Entity\Project;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
/**
 * Projects Model
 *
 * @property \Cake\ORM\Association\HasMany $Members
 * @property \Cake\ORM\Association\HasMany $Metrics
 * @property \Cake\ORM\Association\HasMany $Requirements
 * @property \Cake\ORM\Association\HasMany $Weeklyreports
 */
class ProjectsTable extends Table
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

        $this->table('projects');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('Members', [
            'foreignKey' => 'project_id'
        ]);
        $this->hasMany('Metrics', [
            'foreignKey' => 'project_id'
        ]);
        $this->hasMany('Weeklyreports', [
            'foreignKey' => 'project_id'
        ]);
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
            ->requirePresence('project_name', 'create')
            ->notEmpty('project_name')
            ->add('project_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->add('created_on', 'valid', ['rule' => 'date'])
            ->requirePresence('created_on', 'create')
            ->notEmpty('created_on');

        $validator
            ->add('updated_on', 'valid', ['rule' => 'date'])
            ->allowEmpty('updated_on');

        $validator
            ->add('finished_date', 'valid', ['rule' => 'date'])
            ->allowEmpty('finished_date');

        $validator
            ->allowEmpty('description');

        $validator
            ->add('is_public', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_public', 'create')
            ->notEmpty('is_public');

        return $validator;
    }
    
    public function getPublicProjects(){  
        $projects = TableRegistry::get('Projects');
        $query = $projects
            ->find()
            ->select(['id'])
            ->where(['is_public' => 1])
            ->toArray();
        $publicProjects = array();
        foreach($query as $temp){
            $project = array();
            $project['id'] = $temp->id;
            $publicProjects[] = $project;
        }
        return $publicProjects;
    }
    
    public function getWeeklyreportWeeks($project_id){
        $weeklyreports = TableRegistry::get('Weeklyreports'); 
        $query = $weeklyreports
            ->find()
            ->select(['week', 'year'])
            ->where(['project_id' => $project_id])
            ->toArray();
        
        //print_r($query);
        
        $weeks = array();
        foreach($query as $temp){
            $report = array();
            $report['week'] = $temp->week;
            $report['year'] = $temp->year;
            $weeks[] = $report;
        }
        return $weeks;
    }
}
