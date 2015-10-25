<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $weeklyreport->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyreport->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Weeklyreports'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="weeklyreports form large-9 medium-8 columns content">
    <?= $this->Form->create($weeklyreport) ?>
    <fieldset>
        <legend><?= __('Edit Weeklyreport') ?></legend>
        <?php
            echo $this->Form->input('project_id', ['options' => $projects]);
            echo $this->Form->input('title');
            echo $this->Form->input('date');
            echo $this->Form->input('reqlink');
            echo $this->Form->input('problems');
            echo $this->Form->input('meetings');
            echo $this->Form->input('additional');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
