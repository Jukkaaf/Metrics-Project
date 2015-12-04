<?php echo $this->Highcharts->includeExtraScripts(); ?>

<div class="metrics form large-9 medium-8 columns content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Edit limits') ?></legend>
        <?php
            echo $this->Form->input('weekmin', ['options' => $weekmin]);
            echo $this->Form->input('weekmax', ['options' => $weekmax]);
            echo $this->Form->input('yearmin', ['options' => $yearmin]);
            echo $this->Form->input('yearmax', ['options' => $yearmax]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>


<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Weeklyreports'), ['controller' => 'Weeklyreports', 'action' => 'index']) ?></li> 
    </ul>
</nav>
<div class="metrics index large-9 medium-8 columns content">
    <div class="chart">
        <h4>Phase Chart</h4>
        <div id="phasewrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
        <div class="clear"></div>
        <?php echo $this->Highcharts->render($phaseChart, 'phasechart'); ?>
    </div>   
</div>
<div class="metrics index large-9 medium-8 columns content">   
    <div class="chart">
        <h4>Req Chart</h4>
        <div id="reqwrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
        <div class="clear"></div>
        <?php echo $this->Highcharts->render($reqChart, 'reqchart'); ?>
    </div>
</div>
<div class="metrics index large-9 medium-8 columns content">   
    <div class="chart">
        <h4>Commit Chart</h4>
        <div id="commitwrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
        <div class="clear"></div>
        <?php echo $this->Highcharts->render($commitChart, 'commitchart'); ?>
    </div>
</div>
<div class="metrics index large-9 medium-8 columns content">   
    <div class="chart">
        <h4>Testcase Chart</h4>
        <div id="testcasewrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
        <div class="clear"></div>
        <?php echo $this->Highcharts->render($testcaseChart, 'testcasechart'); ?>
    </div>
</div>