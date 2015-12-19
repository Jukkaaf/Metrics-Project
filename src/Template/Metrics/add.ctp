<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="metrics form large-4 medium-8 columns content float: left">
    <?= $this->Form->create($metric) ?>
    <fieldset>
        <legend><?= __('Add Metric') ?></legend>
        <?php
            echo $this->Form->input('metrictype_id', ['options' => $metrictypes]);
            //echo $this->Form->input('weeklyreport_id', ['empty' => 'No weeklyreport', 'options' => $weeklyreports]);
            echo $this->Form->input('date');
            echo $this->Form->input('value');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
