<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="metrics form large-9 medium-8 columns content">
    <?= $this->Form->create($metric) ?>
    <fieldset>
        <legend><?= __('Add Metric') ?></legend>
        <?php
            echo $this->Form->input('metrictype_id', ['options' => $metrictypes]);
            echo $this->Form->input('weeklyreport_id', ['options' => $weeklyreports]);
            echo $this->Form->input('date');
            echo $this->Form->input('value');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
