<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Weeklyreports'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="weeklyreports form large-9 medium-8 columns content">
    <?= $this->Form->create($weeklyreport) ?>
    <fieldset>
        <legend><?= __('Add Weeklyreport') ?></legend>
        <?php
            $report = $this->request->session()->read('report');           
            echo $this->Form->input('project_id', ['options' => $projects]);
            echo $this->Form->input('title', array('value' => $report['#title']));
            echo $this->Form->input('date', array('value' => $report['actual_date']));
            echo $this->Form->input('reqlink', array('value' => $report['#reglink']));
            echo $this->Form->input('problems', array('value' => $report['#problems']));
            echo $this->Form->input('meetings', array('value' => $report['#meetings']));
            echo $this->Form->input('additional', array('value' => $report['#additional']));
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
