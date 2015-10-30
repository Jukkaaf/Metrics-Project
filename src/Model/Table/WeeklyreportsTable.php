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
    }
    
    // parsing trough the txt files contents to get the information out 
    public function saveUploadedReport($file_content){
        $project_name = "test"; // change to session project
        
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
        $fields_tosave = ["#title", "#time", "#reglink", "#problems", "#meetings", "#additional"];
        $fields_toignore = ["#project", "#Project managers", "#phase", "#req", "#commits",
                            "#passed_test_cases / total_test_cases", "#hours"];
        $temptext = "";
        $saverow = False;
        $key = "";
        
        $rows = explode("\n", $file_content);
        $row_count = count($rows);
        
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
                $temptext = $temptext . $buffer;
            }
        }
        if(!empty($temptext)){
            $report[$key] = $temptext;
        }
        
        // turn date in to the right format
        $date = explode(".", $report['#time']);
        
        $actual_date['year'] = $date[2];
        $actual_date['month'] = $date[1];
        $actual_date['day'] = $date[0];
        
        $report['actual_date'] = $actual_date;
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
