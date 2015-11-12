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
        $this->hasMany('Metrics', [
            'foreignKey' => 'project_id'
        ]);
        $this->hasMany('Workinghours', [
            'foreignKey' => 'member_id'
        ]);
    }
    
    // parsing trough the txt files contents to get the information out 
    public function saveUploadedReport($file_content, $project_name){
        // save it in to the server
        $time = Time::now();
        $filename = $project_name . $time->year . $time->month . $time->day . $time->hour . $time->minute . $time->second .'.txt';
        $path = ROOT . DS . 'reports' . DS . $project_name . DS . $filename;
        #print_r($path);
        $file = new File($path, true, 0644);
        $file->open('w', false);
        $file->write($file_content, 'w', false);
        $file->close();
    }
    public function parseReport($file_content){
        $report = [];
        $fields_tosave = ["#title", "#time", "#reglink", "#problems", "#hours", "#meetings", "#additional"];
        $fields_toignore = ["#project", "#Project managers", "#phase", "#req", "#commits",
                            "#passed_test_cases / total_test_cases"];
        $temptext = "";
        $saverow = False;
        $key = "";
        
        $rows = explode("\n", $file_content);
        $row_count = count($rows);
        
        // read all the important info based on fields_tosave and fields_toignore
        for($count = 0; $count < $row_count; $count++){
            $buffer = str_replace(array("\r", "\n"), '', $rows[$count]);
            
            if(in_array($buffer, $fields_tosave)){
                if(!empty($temptext)){
                    $report[$key] = $temptext;
                    $temptext = "";
                }             
                $saverow = True;
                $key = $buffer;
            }
            else if(in_array($buffer, $fields_toignore)){
                if(!empty($temptext)){
                    $report[$key] = $temptext;
                    $temptext = "";
                    $key = "";
                }              
                $saverow = False;
            }           
            else if($saverow && !empty($buffer)){
                if(!empty($temptext)){
                    $temptext = $temptext . " " . $buffer;
                }
                else{
                    $temptext = $temptext . $buffer;
                }              
            }
        }
        if(!empty($temptext)){
            $report[$key] = $temptext;
        }
        
        // turn date in to the right format
        if(array_key_exists('#time', $report)){
            $date = explode(".", $report['#time']);
        
            $actual_date['year'] = $date[2];
            $actual_date['month'] = $date[1];
            $actual_date['day'] = $date[0];

            $report['actual_date'] = $actual_date;
        }
        // turn the hours in to a usable format
        if(array_key_exists('#hours', $report)){
            $hours = explode(" ", $report['#hours']);
            $hour_count = count($hours);
            $actual_hours = [];
            $count = 0;
            while($count < $hour_count - 2){
                $temp_hours = [];
                
                $temp_hours['firstname'] = $hours[$count];
                $temp_hours['lastname'] = $hours[$count + 1];
                $temp_hours['hours'] = $hours[$count + 2];
                
                $actual_hours[] = $temp_hours;
                
                $count += 5;
            }
            $report['actual_hours_index'] = 0;
            $report['actual_hours'] = $actual_hours;
        }
        
        // add the file content to the array
        $report["file_content"] = $file_content;
        return $report;
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
            ->add('week', 'valid', [
                'rule' => 'numeric',
                // this is the weeknumber range
                // maximum weeknumber is 53
                'rule' => ['range', 1, 53]
                ])
            ->requirePresence('week', 'create')
            ->notEmpty('week');

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
