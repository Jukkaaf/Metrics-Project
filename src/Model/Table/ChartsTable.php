<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class ChartsTable extends Table
{
    
    public function initialize(array $config) {
        parent::initialize($config);
        //$this->loadComponent('Highcharts.Highcharts');
    }
    
    public function reports($project_id, $weekmin, $weekmax, $yearmin, $yearmax){
        $weeklyreports = TableRegistry::get('Weeklyreports');
        $organize = array(); 
        if($yearmin != $yearmax){
            $query = $weeklyreports
                ->find()
                ->select(['id', 'week', 'year'])
                ->where(['project_id' => $project_id, 
                    'week >=' => $weekmin, 
                    'year' => $yearmin]) // min year, weeknum is min or bigger
                ->orWhere(['project_id' => $project_id, 
                    'week <=' => $weekmax, 
                    'year' => $yearmax]) // max year, but weeknumber is max or smaller
                ->toArray();
        
            $query2 = $weeklyreports
                ->find()
                ->select(['id', 'week', 'year'])
                ->Where(['project_id' => $project_id, 
                    'year >' => $yearmin, 'year <' => $yearmax]) // possible middle year, all weeks are good
                ->toArray();

            foreach($query2 as $temp){
                $temparray = array();
                $temparray['id'] = $temp->id;
                $temparray['week'] = $temp->week;
                $temparray['year'] = $temp->year;

                $organize[] = $temparray ;
            }
        }
        else{
            // if min and max years are same then we just look at week limits
            $query = $weeklyreports
                ->find()
                ->select(['id', 'week', 'year'])
                ->where(['project_id' => $project_id, 
                    'week >=' => $weekmin, 'week <=' => $weekmax, 
                    'year' => $yearmin])

                ->toArray();
        }
         
        foreach($query as $temp){
            $temparray = array();
            $temparray['id'] = $temp->id;
            $temparray['week'] = $temp->week;
            $temparray['year'] = $temp->year;

            $organize[] = $temparray ;
        }
        
        $id = array();
        $week = array();
        $year = array();
        foreach ($organize as $key => $row) {
            $id[$key] = $row['id'];
            $week[$key] = $row['week'];
            $year[$key] = $row['year'];
        }
        
        array_multisort($year, SORT_ASC, $week, SORT_ASC, $organize);

        $idlist = array();
        $weeklist = array();
        foreach($organize as $temp){
            $idlist[] = $temp['id'];
            $weeklist[] = $temp['week'];
        }
        
        $data = array();
        $data['id'] = $idlist;
        $data['weeks'] = $weeklist;

        return $data;
    }
    
    public function testcaseAreaData($project_id, $idlist){
        $metrics = TableRegistry::get('Metrics');
        
        $testsPassed = array();
        $testsTotal = array();
        
        foreach($idlist as $temp){
            
            $query2 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 8]) // 7 is the id of commits
                    ->toArray();
            
            $testsPassed[] = $query2[0]->value;
            
            $query3 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 9]) // 7 is the id of commits
                    ->toArray();
            
            $testsTotal[] = $query3[0]->value;
            
        }
        
        $data = array();
        $data['testsPassed'] = $testsPassed;
        $data['testsTotal'] = $testsTotal;
        
        return $data;
    }
    
    public function commitAreaData($project_id, $idlist){
        $metrics = TableRegistry::get('Metrics');

        $commits = array();
        
        foreach($idlist as $temp){
            
            $query2 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 7]) // 7 is the id of commits
                    ->toArray();
            
            $commits[] = $query2[0]->value;
            
        }
        
        $data = array();
        $data['commits'] = $commits;
        
        return $data;
    }
    
    
    public function reqColumnData($project_id, $idlist){
        $metrics = TableRegistry::get('Metrics');

        $new = array();
        $inprogress = array();
        $closed = array();
        $rejected = array();
        
        foreach($idlist as $temp){
            
            $query2 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 3])
                    ->toArray();
            
            $new[] = $query2[0]->value;
            
            $query3 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 4])
                    ->toArray();
            
            $inprogress[] = $query3[0]->value;
            
            $query4 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 5])
                    ->toArray();
            
            $closed[] = $query4[0]->value;
            
            
            $query5 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 6])
                    ->toArray();
            
            $rejected[] = $query5[0]->value;
            
        }

        $data = array();
        $data['new'] = $new;
        $data['inprogress'] = $inprogress;
        $data['closed'] = $closed;
        $data['rejected'] = $rejected;
        
        return $data;
    }
    
    public function phaseAreaData($project_id, $idlist){
        $metrics = TableRegistry::get('Metrics');
        
        $phase = array();
        $phaseTotal = array();
        
        foreach($idlist as $temp){
            
            $query2 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 1])
                    ->toArray();
            
            $phase[] = $query2[0]->value;
            
            $query3 = $metrics
                    ->find()
                    ->select(['value'])
                    ->where(['weeklyreport_id =' => $temp, 'metrictype_id =' => 2])
                    ->toArray();
            
            $phaseTotal[] = $query3[0]->value;
            
        }

        $data = array();
        $data['phase'] = $phase;
        $data['phaseTotal'] = $phaseTotal;
        
        return $data;
    }
    
    public function hoursData($project_id){   
        $members = TableRegistry::get('Members');
        
        // get a list of the members in the project
        $query = $members
                ->find()
                ->select(['id'])
                ->where(['project_id =' => $project_id])
                ->toArray();
        $memberlist = array();
        foreach($query as $temp){
            $memberlist[] = $temp->id;
        }
        
        $workinghours = TableRegistry::get('Workinghours');
        // get all the different work types one by one
        $data = array();
        $query = $workinghours
                ->find()
                ->select(['duration'])
                ->where(['worktype_id =' => 1, 'member_id IN' => $memberlist])
                ->toArray();
        $num = 0;
        // count the total ammount of the duration of the worktype
        foreach($query as $temp){
            $num += $temp->duration;
        }
        $data['management'] = $num;
        
        $query = $workinghours
                ->find()
                ->select(['duration'])
                ->where(['worktype_id =' => 2, 'member_id IN' => $memberlist])
                ->toArray();
        $num = 0;
        foreach($query as $temp){
            $num += $temp->duration;
        }
        $data['code'] = $num;
        
        $query = $workinghours
                ->find()
                ->select(['duration'])
                ->where(['worktype_id =' => 3, 'member_id IN' => $memberlist])
                ->toArray();
        $num = 0;
        foreach($query as $temp){
            $num += $temp->duration;
        }
        $data['document'] = $num;
        
        $query = $workinghours
                ->find()
                ->select(['duration'])
                ->where(['worktype_id =' => 4, 'member_id IN' => $memberlist])
                ->toArray();
        $num = 0;
        foreach($query as $temp){
            $num += $temp->duration;
        }
        $data['study'] = $num;
        
        $query = $workinghours
                ->find()
                ->select(['duration'])
                ->where(['worktype_id =' => 5, 'member_id IN' => $memberlist])
                ->toArray();
        $num = 0;
        foreach($query as $temp){
            $num += $temp->duration;
        }
        $data['other'] = $num;
        
        return $data;
    }
    
    public function weeklyhourAreaData($project_id, $idlist){
        $weeklyhours = TableRegistry::get('Weeklyhours');
        
        $weeklyhourData = array();
        
        foreach($idlist as $temp){  
            $query = $weeklyhours
                    ->find()
                    ->select(['duration'])
                    ->where(['weeklyreport_id =' => $temp])
                    ->toArray();
            $duration = 0;
            foreach($query as $dur){
                $duration += $dur->duration;
            }
            $weeklyhourData[] = $duration;
        }
        
        return $weeklyhourData;
    }
}
