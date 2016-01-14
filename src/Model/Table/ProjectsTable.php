<?php
namespace App\Model\Table;

use App\Model\Entity\Project;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class ProjectsTable extends Table
{
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
    
    // return a list of all the public projects
    public function getPublicProjects(){  
        $projects = TableRegistry::get('Projects');
        $query = $projects
            ->find()
            ->select(['id', 'project_name'])
            ->where(['is_public' => 1])
            ->toArray();
        $publicProjects = array();
        foreach($query as $temp){
            $project = array();
            $project['id'] = $temp->id;
            $project['project_name'] = $temp->project_name;
            $publicProjects[] = $project;
        }
        return $publicProjects;
    }
    
    // get the total weeklyhours of a project
    public function getWeeklyhoursDuration($project_id){
        $weeklyreports = TableRegistry::get('Weeklyreports'); 
        $query = $weeklyreports
            ->find()
            ->select(['id'])
            ->where(['project_id' => $project_id])
            ->toArray();
        $ids = array();
        foreach($query as $temp){
            $ids[] = $temp->id;
        }
        
        $weeklyhours = TableRegistry::get('Weeklyhours'); 
        $query = $weeklyhours
            ->find()
            ->select(['duration'])
            ->where(['weeklyreport_id IN' => $ids])
            ->toArray();
        $duration = 0;
        foreach($query as $temp){
            $duration += $temp->duration;
        }
        
        return $duration;
    }
    
    // get a list with 'X', 'L' or ' ' for the weeks based on the limits
    // 'X' if that weeks report was returned
    // 'L' if that weeks report should have been returned but its not
    // ' ' if there is no report but its still not late
    public function getWeeklyreportWeeks($project_id, $min, $max, $year){
        $weeklyreports = TableRegistry::get('Weeklyreports'); 
        $query = $weeklyreports
            ->find()
            ->select(['week'])
            ->where(['project_id' => $project_id, 'week >' => $min, 'week <' => $max, 'year' => $year])
            ->toArray();

        $weeks = array();
        foreach($query as $temp){
            $weeks[] = $temp->week;
        }
        $time = Time::now();
        // with the weeks when the report has not been filled
        $completeList = array();
        for($count = $min; $count <= $max; $count++){
            // if the week is found, 'X'
            if(in_array($count, $weeks)){
                $completeList[] = 'X'; 
            }
            else{
                // late,
                // if its the week after the week in question and its this year and its a weekday after tuesday
                // or the current weeknumber is over 1 more than weeknumber in question and its the same year
                // or its just the next year
                if(($time->weekOfYear == $count + 1 && $time->year == $year && $time->dayOfWeek > 2) 
                    || ($time->weekOfYear > $count + 1 && $time->year == $year) 
                    || ($time->year > $year)){
                    $completeList[] = 'L';
                }
                // if its not late, but there is no report
                else{
                    $completeList[] = ' '; 
                } 
            } 
        }
        return $completeList;
    }
}
