<?php
namespace App\Controller;

use App\Controller\AppController;
use Highcharts\Controller\Component\HighchartsComponent;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ChartsController extends AppController
{
    public $name = 'Charts';
    public $helpers = ['Highcharts.Highcharts'];
    public $uses = array();
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Highcharts.Highcharts');
    }
    
    public function index() {
        // When the chart limits are updated this is where they are saved
        if ($this->request->is('post')) {
            $data = $this->request->data;
            
            $chart_limits['weekmin'] = $data['weekmin'];
            $chart_limits['weekmax'] = $data['weekmax'];
            $chart_limits['yearmin'] = $data['yearmin'];
            $chart_limits['yearmax'] = $data['yearmax'];
            
            $this->request->session()->write('chart_limits', $chart_limits);
            // refreshin the page to apply the new limits
            $page = $_SERVER['PHP_SELF'];
        }
        // Set the stock limits for the chart limits
        // They are only set once, if the "chart_limits" cookie is not in the session
        if(!$this->request->session()->check('chart_limits')){
            $time = Time::now();
            // show last year, current year and next year
            $chart_limits['weekmin'] = 0;
            $chart_limits['weekmax'] =  53;
            $chart_limits['yearmin'] = $time->year - 1;
            $chart_limits['yearmax'] = $time->year;
            
            $this->request->session()->write('chart_limits', $chart_limits);
        }
        // Loadin the limits to a variable
        $chart_limits = $this->request->session()->read('chart_limits');
        // The ID of the currently selected project
        $project_id = $this->request->session()->read('selected_project')['id'];
        
        // Get the chart objects for the charts
        // these objects come from functions in this controller
        $phaseChart = $this->phaseChart();
        $reqChart = $this->reqChart();
        $commitChart = $this->commitChart();
        $testcaseChart = $this->testcaseChart();
        $hoursChart = $this->hoursChart();
        $weeklyhourChart = $this->weeklyhourChart();
        $reqPercentChart = $this->reqPercentChart();    
        
        // Get all the data for the charts, based on the chartlimits
        // Fuctions in "ChartsTable.php"
        $weeklyreports = $this->Charts->reports($project_id, $chart_limits['weekmin'], $chart_limits['weekmax'], $chart_limits['yearmin'], $chart_limits['yearmax']);
        $phaseData = $this->Charts->phaseAreaData($weeklyreports['id']);
        $reqData = $this->Charts->reqColumnData($weeklyreports['id']);
        $commitData = $this->Charts->commitAreaData($weeklyreports['id']);
        $testcaseData = $this->Charts->testcaseAreaData($weeklyreports['id']);
        $hoursData = $this->Charts->hoursData($project_id);
        $weeklyhourData = $this->Charts->weeklyhourAreaData($weeklyreports['id']);
        
        // Insert the data in to the charts, one by one
        // phaseChart
        $phaseChart->xAxis->categories = $weeklyreports['weeks'];
        $phaseChart->series[] = array(
            'name' => 'Total phases',
            'data' => $phaseData['phaseTotal']
        );
        $phaseChart->series[] = array(
            'name' => 'Phase',
            'data' => $phaseData['phase']
        );
        
        // reqChart
        $reqChart->xAxis->categories = $weeklyreports['weeks'];
        $reqChart->series[] = array(
            'name' => 'New',
            'data' => $reqData['new']
        );
        $reqChart->series[] = array(
            'name' => 'In progress',
            'data' => $reqData['inprogress']
        );
        $reqChart->series[] = array(
            'name' => 'Closed',
            'data' => $reqData['closed']
        );
        $reqChart->series[] = array(
            'name' => 'Rejected',
            'data' => $reqData['rejected']
        );
        
        // commitChart
        $commitChart->xAxis->categories = $weeklyreports['weeks'];    
        $commitChart->series[] = array(
            'name' => 'commits',
            'data' => $commitData['commits']
        );

        // testcaseChart
        $testcaseChart->xAxis->categories = $weeklyreports['weeks'];
        $testcaseChart->series[] = array(
            'name' => 'Total tests',
            'data' => $testcaseData['testsTotal']
        );
        $testcaseChart->series[] = array(
            'name' => 'Passed tests',
            'data' => $testcaseData['testsPassed']
        );

        // hoursChart
        $hoursChart->series[] = array(
            'name' => 'Management',
            'data' => array(
                $hoursData['management'],
                $hoursData['code'],
                $hoursData['document'],
                $hoursData['study'],
                $hoursData['other']
            )
        );
        
        // weeklyhourChart
        $weeklyhourChart->xAxis->categories = $weeklyreports['weeks'];    
        $weeklyhourChart->series[] = array(
            'name' => 'weekly hours',
            'data' => $weeklyhourData
        );
        
        // reqPercentChart
        $reqPercentChart->xAxis->categories = $weeklyreports['weeks'];
        $reqPercentChart->series[] = array(
            'name' => 'New',
            'data' => $reqData['new']
        );
        $reqPercentChart->series[] = array(
            'name' => 'In progress',
            'data' => $reqData['inprogress']
        );
        $reqPercentChart->series[] = array(
            'name' => 'Closed',
            'data' => $reqData['closed']
        );
        $reqPercentChart->series[] = array(
            'name' => 'Rejected',
            'data' => $reqData['rejected']
        );
        
        // This sets the charts visible in the actual charts page "Charts/index.php"
        $this->set(compact('phaseChart', 'reqChart', 'commitChart', 'testcaseChart', 'hoursChart', 'weeklyhourChart', 'reqPercentChart'));
    }
    
    public function reqPercentChart() {
        $myChart = $this->Highcharts->createChart();

        $myChart->chart->renderTo = "reqpercentwrapper";
        $myChart->chart->type = "column";
        $myChart->title->text = "Stacked Column Chart";

        $myChart->yAxis->min = 0;
        $myChart->yAxis->title->text = "Total Fruit Consumption";

        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr(
            "function() {
            return ''+ this.series.name +': '+ this.y +' ('+ Math.round(this.percentage) +'%)';}");

        $myChart->plotOptions->column->stacking = "percent";
        
        return $myChart;
    }
    
    
    // All the following functions are similar
    // They create a custom chart object and return it
    // Unfortunately the functions have to be in the controller, 
    // because the chart objects cannot be created outside of the controller
    
    public function weeklyhourChart(){
        $myChart = $this->Highcharts->createChart();

        $myChart->title = array(
            'text' => 'Weekly hours', 
            'x' => 20,
            'y' => 20,
            'align' => 'left',
            'styleFont' => '18px Metrophobic, Arial, sans-serif',
            'styleColor' => '#0099ff',
        );

        $myChart->chart->renderTo = 'weeklyhourwrapper';
        $myChart->chart->type = 'area';
        $myChart->chart->width =  800;
        $myChart->chart->height = 600;
        $myChart->chart->marginTop = 60;
        $myChart->chart->marginLeft = 90;
        $myChart->chart->marginRight = 30;
        $myChart->chart->marginBottom = 110;
        $myChart->chart->spacingRight = 10;
        $myChart->chart->spacingBottom = 15;
        $myChart->chart->spacingLeft = 0;
        $myChart->chart->alignTicks = FALSE;
        $myChart->chart->backgroundColor->linearGradient = array(0, 0, 0, 300);
        $myChart->chart->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));                

        $myChart->title->text = 'Weekly hours';
        $myChart->xAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->yAxis->title->text = 'temp';
        $myChart->yAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr("function() {
        return this.series.name +' produced <b>'+
        Highcharts.numberFormat(this.y, 0) +'</b><br/>Week number '+ this.x;}");
        $myChart->plotOptions->area->marker->enabled = false;
        $myChart->plotOptions->area->marker->symbol = 'circle';
        $myChart->plotOptions->area->marker->radius = 2;
        $myChart->plotOptions->area->marker->states->hover->enabled = true;

        $myChart->legend->enabled = true;
        $myChart->legend->layout = 'horizontal';
        $myChart->legend->align = 'center';
        $myChart->legend->verticalAlign  = 'bottom';
        $myChart->legend->itemStyle = array('color' => '#222');
        $myChart->legend->backgroundColor->linearGradient = array(0, 0, 0, 25);
        $myChart->legend->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));

        $myChart->xAxis->labels->enabled = true;
        $myChart->xAxis->labels->align = 'right';
        $myChart->xAxis->labels->step = 1;
        $myChart->xAxis->labels->x = 5;
        $myChart->xAxis->labels->y = 20;
        $myChart->yAxis->title->text = 'Weekly hours from reports';
        $myChart->enable->autoStep = false;
        $myChart->credits->enabled = true;
        $myChart->credits->text = 'Example.com';
        $myChart->credits->href = 'http://example.com';

        return $myChart;
    }
    
    public function testcaseChart() {
        $myChart = $this->Highcharts->createChart();

        $myChart->title = array(
            'text' => 'Test Cases', 
            'x' => 20,
            'y' => 20,
            'align' => 'left',
            'styleFont' => '18px Metrophobic, Arial, sans-serif',
            'styleColor' => '#0099ff',
        );

        $myChart->chart->renderTo = 'testcasewrapper';
        $myChart->chart->type = 'area';
        $myChart->chart->width =  800;
        $myChart->chart->height = 600;
        $myChart->chart->marginTop = 60;
        $myChart->chart->marginLeft = 90;
        $myChart->chart->marginRight = 30;
        $myChart->chart->marginBottom = 110;
        $myChart->chart->spacingRight = 10;
        $myChart->chart->spacingBottom = 15;
        $myChart->chart->spacingLeft = 0;
        $myChart->chart->alignTicks = FALSE;
        $myChart->chart->backgroundColor->linearGradient = array(0, 0, 0, 300);
        $myChart->chart->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));                

        $myChart->title->text = 'Test cases area';
        //$myChart->subtitle->text = "Source: <a href=\"http://thebulletin.metapress.com/content/c4120650912x74k7/fulltext.pdf\">thebulletin.metapress.com</a>";
        $myChart->xAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->yAxis->title->text = 'temp';
        $myChart->yAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr("function() {
        return this.series.name +' <b>'+
        Highcharts.numberFormat(this.y, 0) +'</b><br/>Week number '+ this.x;}");
        $myChart->plotOptions->area->marker->enabled = false;
        $myChart->plotOptions->area->marker->symbol = 'circle';
        $myChart->plotOptions->area->marker->radius = 2;
        $myChart->plotOptions->area->marker->states->hover->enabled = true;

        $myChart->legend->enabled = true;
        $myChart->legend->layout = 'horizontal';
        $myChart->legend->align = 'center';
        $myChart->legend->verticalAlign  = 'bottom';
        $myChart->legend->itemStyle = array('color' => '#222');
        $myChart->legend->backgroundColor->linearGradient = array(0, 0, 0, 25);
        $myChart->legend->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));

        $myChart->xAxis->labels->enabled = true;
        $myChart->xAxis->labels->align = 'right';
        $myChart->xAxis->labels->step = 1;
        $myChart->xAxis->labels->x = 5;
        $myChart->xAxis->labels->y = 20;
        $myChart->yAxis->title->text = 'Ammount of test cases';
        $myChart->enable->autoStep = false;
        // credits setting  [Highcharts.com  displayed on chart]
        $myChart->credits->enabled = true;
        $myChart->credits->text = 'Example.com';
        $myChart->credits->href = 'http://example.com';
        
        return $myChart;
    }
    
    public function commitChart(){
        $myChart = $this->Highcharts->createChart();

        $myChart->title = array(
            'text' => 'Commits', 
            'x' => 20,
            'y' => 20,
            'align' => 'left',
            'styleFont' => '18px Metrophobic, Arial, sans-serif',
            'styleColor' => '#0099ff',
        );

        $myChart->chart->renderTo = 'commitwrapper';
        $myChart->chart->type = 'area';
        $myChart->chart->width =  800;
        $myChart->chart->height = 600;
        $myChart->chart->marginTop = 60;
        $myChart->chart->marginLeft = 90;
        $myChart->chart->marginRight = 30;
        $myChart->chart->marginBottom = 110;
        $myChart->chart->spacingRight = 10;
        $myChart->chart->spacingBottom = 15;
        $myChart->chart->spacingLeft = 0;
        $myChart->chart->alignTicks = FALSE;
        $myChart->chart->backgroundColor->linearGradient = array(0, 0, 0, 300);
        $myChart->chart->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));                

        $myChart->title->text = 'Commits';
        $myChart->xAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->yAxis->title->text = 'temp';
        $myChart->yAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr("function() {
        return this.series.name +' produced <b>'+
        Highcharts.numberFormat(this.y, 0) +'</b><br/>Week number '+ this.x;}");
        $myChart->plotOptions->area->marker->enabled = false;
        $myChart->plotOptions->area->marker->symbol = 'circle';
        $myChart->plotOptions->area->marker->radius = 2;
        $myChart->plotOptions->area->marker->states->hover->enabled = true;

        $myChart->legend->enabled = true;
        $myChart->legend->layout = 'horizontal';
        $myChart->legend->align = 'center';
        $myChart->legend->verticalAlign  = 'bottom';
        $myChart->legend->itemStyle = array('color' => '#222');
        $myChart->legend->backgroundColor->linearGradient = array(0, 0, 0, 25);
        $myChart->legend->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));

        $myChart->xAxis->labels->enabled = true;
        $myChart->xAxis->labels->align = 'right';
        $myChart->xAxis->labels->step = 1;
        $myChart->xAxis->labels->x = 5;
        $myChart->xAxis->labels->y = 20;
        $myChart->yAxis->title->text = 'Ammount of commits';
        $myChart->enable->autoStep = false;
        $myChart->credits->enabled = true;
        $myChart->credits->text = 'Example.com';
        $myChart->credits->href = 'http://example.com';

        return $myChart;
    }
    
    public function reqChart() {
        $myChart = $this->Highcharts->createChart();

        $myChart->chart->renderTo = 'reqwrapper';
        $myChart->chart->type = 'column';
        $myChart->title->text = 'Requirements';
        $myChart->subtitle->text = 'req';

        $myChart->yAxis->min = 0;
        $myChart->yAxis->title->text = 'Requirements';
        $myChart->legend->layout = 'vertical';
        $myChart->legend->backgroundColor = '#FFFFFF';
        $myChart->legend->align = 'left';
        $myChart->legend->verticalAlign = 'top';
        $myChart->legend->x = 100;
        $myChart->legend->y = 70;
        $myChart->legend->floating = 1;
        $myChart->legend->shadow = 1;

        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr("function() {
            return this.y;}");

        $myChart->plotOptions->column->pointPadding = 0.2;
        $myChart->plotOptions->column->borderWidth = 0;

        return $myChart;
    }
    
    public function phaseChart(){
        $chartName = 'Area Chart';

        $myChart = $this->Highcharts->createChart();

        $myChart->title = array(
            'text' => 'Phases', 
            'x' => 20,
            'y' => 20,
            'align' => 'left',
            'styleFont' => '18px Metrophobic, Arial, sans-serif',
            'styleColor' => '#0099ff',
        );

        $myChart->chart->renderTo = 'phasewrapper';
        $myChart->chart->type = 'area';
        $myChart->chart->width =  800;
        $myChart->chart->height = 600;
        $myChart->chart->marginTop = 60;
        $myChart->chart->marginLeft = 90;
        $myChart->chart->marginRight = 30;
        $myChart->chart->marginBottom = 110;
        $myChart->chart->spacingRight = 10;
        $myChart->chart->spacingBottom = 15;
        $myChart->chart->spacingLeft = 0;
        $myChart->chart->alignTicks = FALSE;
        $myChart->chart->backgroundColor->linearGradient = array(0, 0, 0, 300);
        $myChart->chart->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));                

        $myChart->title->text = 'Phases';
        //$myChart->subtitle->text = "Source: <a href=\"http://thebulletin.metapress.com/content/c4120650912x74k7/fulltext.pdf\">thebulletin.metapress.com</a>";
        $myChart->xAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->yAxis->title->text = 'temp';
        $myChart->yAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr("function() {
        return this.series.name +' <b>'+
        Highcharts.numberFormat(this.y, 0) +'</b><br/>Week number '+ this.x;}");
        $myChart->plotOptions->area->marker->enabled = false;
        $myChart->plotOptions->area->marker->symbol = 'circle';
        $myChart->plotOptions->area->marker->radius = 2;
        $myChart->plotOptions->area->marker->states->hover->enabled = true;

        $myChart->legend->enabled = true;
        $myChart->legend->layout = 'horizontal';
        $myChart->legend->align = 'center';
        $myChart->legend->verticalAlign  = 'bottom';
        $myChart->legend->itemStyle = array('color' => '#222');
        $myChart->legend->backgroundColor->linearGradient = array(0, 0, 0, 25);
        $myChart->legend->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));

        $myChart->xAxis->labels->enabled = true;
        $myChart->xAxis->labels->align = 'right';
        $myChart->xAxis->labels->step = 1;
        $myChart->xAxis->labels->x = 5;
        $myChart->xAxis->labels->y = 20;
        $myChart->yAxis->title->text = 'Ammount Phases';
        $myChart->enable->autoStep = false;
        // credits setting  [Highcharts.com  displayed on chart]
        $myChart->credits->enabled = true;
        $myChart->credits->text = 'Example.com';
        $myChart->credits->href = 'http://example.com';
        
        return $myChart;
    }
    
    public function hoursChart(){
        $chartName = 'Area Chart';

        $myChart = $this->Highcharts->createChart();

        $myChart->title = array(
            'text' => 'Hours (stacked)', 
            'x' => 20,
            'y' => 20,
            'align' => 'left',
            'styleFont' => '18px Metrophobic, Arial, sans-serif',
            'styleColor' => '#0099ff',
        );

        $myChart->chart->renderTo = 'hourswrapper';
        $myChart->chart->type = 'column';
        $myChart->chart->width =  800;
        $myChart->chart->height = 600;
        $myChart->chart->marginTop = 60;
        $myChart->chart->marginLeft = 90;
        $myChart->chart->marginRight = 30;
        $myChart->chart->marginBottom = 110;
        $myChart->chart->spacingRight = 10;
        $myChart->chart->spacingBottom = 15;
        $myChart->chart->spacingLeft = 0;
        $myChart->chart->alignTicks = FALSE;
        $myChart->chart->backgroundColor->linearGradient = array(0, 0, 0, 300);
        $myChart->chart->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));                

        $myChart->title->text = 'Hours';
        $myChart->xAxis->categories = array(
                    'Planning and management',
                    'Coding and testing',
                    'Studying',
                    'Documentation',
                    'Other'
        );
        $myChart->xAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->yAxis->title->text = 'temp';
        $myChart->yAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr("function() {
        return this.series.name +' <b>'+
        Highcharts.numberFormat(this.y, 0) +'</b><br/>Week number '+ this.x;}");
        $myChart->plotOptions->area->marker->enabled = false;
        $myChart->plotOptions->area->marker->symbol = 'circle';
        $myChart->plotOptions->area->marker->radius = 2;
        $myChart->plotOptions->area->marker->states->hover->enabled = true;

        $myChart->legend->enabled = true;
        $myChart->legend->layout = 'horizontal';
        $myChart->legend->align = 'center';
        $myChart->legend->verticalAlign  = 'bottom';
        $myChart->legend->itemStyle = array('color' => '#222');
        $myChart->legend->backgroundColor->linearGradient = array(0, 0, 0, 25);
        $myChart->legend->backgroundColor->stops = array(array(0, 'rgb(217, 217, 217)'), array(1, 'rgb(255, 255, 255)'));

        $myChart->plotOptions->column->stacking = "normal";
        $myChart->plotOptions->column->dataLabels->enabled = 1;
        $myChart->plotOptions->column->dataLabels->color = $this->Highcharts->createJsExpr(
        "(Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'");
        $myChart->xAxis->labels->enabled = true;
        $myChart->xAxis->labels->align = 'right';
        $myChart->xAxis->labels->step = 1;
        $myChart->xAxis->labels->x = 5;
        $myChart->xAxis->labels->y = 20;
        $myChart->yAxis->title->text = 'Total hours';
        $myChart->enable->autoStep = false;
        // credits setting  [Highcharts.com  displayed on chart]
        $myChart->credits->enabled = true;
        $myChart->credits->text = 'Example.com';
        $myChart->credits->href = 'http://example.com';
        
        return $myChart;
    } 
    
    public function isAuthorized($user)
    {      
        return True;
    }
}
