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
        if ($this->request->is('post')) {
            $data = $this->request->data;
            
            $chart_limits['weekmin'] = $data['weekmin'];
            $chart_limits['weekmax'] = $data['weekmax'];
            $chart_limits['yearmin'] = $data['yearmin'];
            $chart_limits['yearmax'] = $data['yearmax'];
            
            $this->request->session()->write('chart_limits', $chart_limits);
            $page = $_SERVER['PHP_SELF'];
        }

        // get the chart objects
        $phaseChart = $this->phaseChart();
        $reqChart = $this->reqChart();
        $commitChart = $this->commitChart();
        $testcaseChart = $this->testcaseChart();
        $hoursChart = $this->hoursChart();
        
        $project_id = $this->request->session()->read('selected_project')['id'];
        
        // figure out the limits for the metrics that go in the chart
        if(!$this->request->session()->check('chart_limits')){
            $time = Time::now();
            // show last year, current year and next year
            $chart_limits['weekmin'] = 0;
            $chart_limits['weekmax'] =  53;
            $chart_limits['yearmin'] = $time->year - 1;
            $chart_limits['yearmax'] = $time->year;
            
            $this->request->session()->write('chart_limits', $chart_limits);
        }
        $chart_limits = $this->request->session()->read('chart_limits');
        
        $weekmin = array(1 => 1, 2 => 2, 12 => 12, 34 => 34, 44 => 44, 53 => 53);
        $weekmax = array(1 => 1, 2 => 2, 12 => 12, 34 => 34, 44 => 44, 53 => 53);
        $yearmin = array(2014 => 2014, 2015 => 2015, 2016 => 2016);
        $yearmax = array(2014 => 2014, 2015 => 2015, 2016 => 2016);
        
        // get all the data
        $weeklyreports = $this->Charts->reports($project_id, $chart_limits['weekmin'], $chart_limits['weekmax'], $chart_limits['yearmin'], $chart_limits['yearmax']);
        $phaseData = $this->Charts->phaseAreaData($project_id, $weeklyreports['id']);
        $reqData = $this->Charts->reqColumnData($project_id, $weeklyreports['id']);
        $commitData = $this->Charts->commitAreaData($project_id, $weeklyreports['id']);
        $testcaseData = $this->Charts->testcaseAreaData($project_id, $weeklyreports['id']);
        $hoursData = $this->Charts->hoursData($project_id);
        
        // insert the data in to the charts
        $phaseChart->xAxis->categories = $weeklyreports['weeks'];
        $phaseChart->series[] = array(
            'name' => 'Total phases',
            'data' => $phaseData['phaseTotal']
        );
        $phaseChart->series[] = array(
            'name' => 'Phase',
            'data' => $phaseData['phase']
        );
        
        
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
        
     
        $commitChart->xAxis->categories = $weeklyreports['weeks'];    
        $commitChart->series[] = array(
            'name' => 'commits',
            'data' => $commitData['commits']
        );

        
        $testcaseChart->xAxis->categories = $weeklyreports['weeks'];
        $testcaseChart->series[] = array(
            'name' => 'Total tests',
            'data' => $testcaseData['testsTotal']
        );
        $testcaseChart->series[] = array(
            'name' => 'Passed tests',
            'data' => $testcaseData['testsPassed']
        );
        
        //new chart: hours stacked by work type         
        //$hoursChart->xAxis->categories = $weeklyreports['weeks'];
        //Different expression here: see line 376 for multiple categories

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
        $this->set(compact('phaseChart', 'reqChart', 'commitChart', 'testcaseChart', 'hoursChart', 'weekmin', 'weekmax', 'yearmin', 'yearmax'));
    }
    
    public function testcaseChart() {
        $myChart = $this->Highcharts->createChart();

        $myChart->title = array(
            'text' => 'Test Cases demo', 
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
            'text' => 'Commits demo', 
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

        $myChart->title->text = 'Commits demo, area';
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
    
    public function area() {
        $chartName = 'Area Chart';

        $myChart = $this->Highcharts->createChart();

        $myChart->title = array(
            'text' => 'Monthly Average Temperature', 
            'x' => 20,
            'y' => 20,
            'align' => 'left',
            'styleFont' => '18px Metrophobic, Arial, sans-serif',
            'styleColor' => '#0099ff',
        );

        $myChart->chart->renderTo = 'areawrapper';
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

        $myChart->title->text = 'US and USSR nuclear stockpiles';
        $myChart->subtitle->text = "Source: <a href=\"http://thebulletin.metapress.com/content/c4120650912x74k7/fulltext.pdf\">thebulletin.metapress.com</a>";
        $myChart->xAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value;}");
        $myChart->yAxis->title->text = 'Nuclear weapon states';
        $myChart->yAxis->labels->formatter = $this->Highcharts->createJsExpr("function() { return this.value / 1000 +'k';}");
        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr("function() {
        return this.series.name +' produced <b>'+
        Highcharts.numberFormat(this.y, 0) +'</b><br/>warheads in '+ this.x;}");
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
        $myChart->xAxis->labels->step = 2;
        $myChart->xAxis->labels->x = 5;
        $myChart->xAxis->labels->y = 20;
        $myChart->xAxis->categories = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $myChart->yAxis->title->text = 'Units Sold';
        $myChart->enable->autoStep = false;
        // credits setting  [Highcharts.com  displayed on chart]
        $myChart->credits->enabled = true;
        $myChart->credits->text = 'Example.com';
        $myChart->credits->href = 'http://example.com';

        $myChart->series[] = array(
            'name' => 'USA',
            'data' => $this->chartData
        );

        $myChart->series[] = array(
            'name' => 'USSR/Russia',
            'data' => $this->chartData2
        );

        $this->set(compact('myChart', 'chartName'));
    }
    
    public function column() {
        $chartName = 'Column Chart';

        $myChart = $this->Highcharts->createChart();

        $myChart->chart->renderTo = 'columnwrapper';
        $myChart->chart->type = 'column';
        $myChart->title->text = 'Monthly Average Rainfall';
        $myChart->subtitle->text = 'Source: WorldClimate.com';

        $myChart->xAxis->categories = array(
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        );

        $myChart->yAxis->min = 0;
        $myChart->yAxis->title->text = 'Rainfall (mm)';
        $myChart->legend->layout = 'vertical';
        $myChart->legend->backgroundColor = '#FFFFFF';
        $myChart->legend->align = 'left';
        $myChart->legend->verticalAlign = 'top';
        $myChart->legend->x = 100;
        $myChart->legend->y = 70;
        $myChart->legend->floating = 1;
        $myChart->legend->shadow = 1;

        $myChart->tooltip->formatter = $this->Highcharts->createJsExpr("function() {
            return '' + this.x +': '+ this.y +' mm';}");

        $myChart->plotOptions->column->pointPadding = 0.2;
        $myChart->plotOptions->column->borderWidth = 0;

        $myChart->series[] = array(
            'name' => 'Tokyo',
            'data' => array(
                49.9,
                71.5,
                106.4,
                129.2,
                144.0,
                176.0,
                135.6,
                148.5,
                216.4,
                194.1,
                95.6,
                54.4
            )
        );

        $myChart->series[] = array(
            'name' => 'New York',
            'data' => array(
                83.6,
                78.8,
                98.5,
                93.4,
                106.0,
                84.5,
                105.0,
                104.3,
                91.2,
                83.5,
                106.6,
                92.3
            )
        );

        $myChart->series[] = array(
            'name' => 'London',
            'data' => array(
                48.9,
                38.8,
                39.3,
                41.4,
                47.0,
                48.3,
                59.0,
                59.6,
                52.4,
                65.2,
                59.3,
                51.2
            )
        );

        $myChart->series[] = array(
            'name' => 'Berlin',
            'data' => array(
                42.4,
                33.2,
                34.5,
                39.7,
                52.6,
                75.5,
                57.4,
                60.4,
                47.6,
                39.1,
                46.8,
                51.1
            )
        );

        $this->set(compact('myChart', 'chartName'));
    }
    
    public function pie() {           
        $pieData = array(
            array(
                'name' => 'Chrome',
                'y' => 45.0,
                'sliced' => true,
                'selected' => true
            ),
            array('IE', 26.8),
            array('Firefox', 12.8),
            array('Safari', 8.5),
            array('Opera', 6.2),
            array('Others', 0.7)
        );

        $chartName = 'Pie Chart';

        $pieChart = $this->Highcharts->createChart();

        $pieChart->chart->renderTo = 'piewrapper';
        $pieChart->chart->plotBackgroundColor = null;
        $pieChart->chart->plotBorderWidth = null;
        $pieChart->chart->plotShadow = false;
        $pieChart->title->text = 'Browser market shares at a specific website, 2010';

        $pieChart->tooltip->formatter = $this->Highcharts->createJsExpr(
            "function() {
            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';}");

        $pieChart->plotOptions->pie->allowPointSelect = 1;
        $pieChart->plotOptions->pie->cursor = 'pointer';
        $pieChart->plotOptions->pie->dataLabels->enabled = 1;
        $pieChart->plotOptions->pie->dataLabels->color = '#000000';
        $pieChart->plotOptions->pie->dataLabels->connectorColor = '#000000';

        $pieChart->plotOptions->pie->dataLabels->formatter = $this->Highcharts->createJsExpr(
            "function() {
            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %'; }");

        $pieChart->series[] = array(
            'type' => 'pie',
            'name' => 'Browser share',
            'data' => array(
                array(
                    'Firefox',
                    45
                ),
                array(
                    'IE',
                    26.8
                ),
                array(
                    'name' => 'Chrome',
                    'y' => 12.8,
                    'sliced' => true,
                    'selected' => true
                ),
                array(
                    'Safari',
                    8.5
                ),
                array(
                    'Opera',
                    6.2
                ),
                array(
                    'Others',
                    0.7
                )
            )
        );

        $this->set(compact('pieChart', 'chartName'));
    }    
        
    public function isAuthorized($user)
    {      
        return True;
    }
}
