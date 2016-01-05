<?php echo $this->Highcharts->includeExtraScripts(); ?>

<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li> 
        <?= $this->Form->create() ?>
        <fieldset>
            <legend><?= __('Edit limits') ?></legend>
            <?php
                echo $this->Form->input('weekmin', array('type' => 'number', 'value' => $this->request->session()->read('chart_limits')['weekmin']));
                echo $this->Form->input('weekmax', array('type' => 'number', 'value' => $this->request->session()->read('chart_limits')['weekmax']));
                echo $this->Form->input('yearmin', array('type' => 'number', 'value' => $this->request->session()->read('chart_limits')['yearmin']));
                echo $this->Form->input('yearmax', array('type' => 'number', 'value' => $this->request->session()->read('chart_limits')['yearmax']));
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </ul>
</nav>
<div class="metrics index large-9 medium-8 columns content float: left">
    <div class="chart">
        <h4>Phase Chart</h4>
        <div id="phasewrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
        <div class="clear"></div>
        <?php echo $this->Highcharts->render($phaseChart, 'phasechart'); ?>
    </div>
    <div class="metrics index large-9 medium-8 columns content float: left">   
    <div class="chart">
        <h4>Req Chart</h4>
        <div id="reqwrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
        <div class="clear"></div>
        <?php echo $this->Highcharts->render($reqChart, 'reqchart'); ?>
    </div>
    </div>
    <div class="metrics index large-9 medium-8 columns content float: left">   
        <div class="chart">
            <h4>Commit Chart</h4>
            <div id="commitwrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
            <div class="clear"></div>
            <?php echo $this->Highcharts->render($commitChart, 'commitchart'); ?>
        </div>
    </div>
    <div class="metrics index large-9 medium-8 columns content float: left">   
        <div class="chart">
            <h4>Testcase Chart</h4>
            <div id="testcasewrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
            <div class="clear"></div>
            <?php echo $this->Highcharts->render($testcaseChart, 'testcasechart'); ?>
        </div>
    </div>
    <div class="metrics index large-9 medium-8 columns content float: left">   
        <div class="chart">
            <h4>Total Hours Chart</h4>
            <div id="hourswrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
            <div class="clear"></div>
            <?php echo $this->Highcharts->render($hoursChart, 'hourschart'); ?>
        </div>
    </div>
</div>
