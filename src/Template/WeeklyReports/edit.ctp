<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $weeklyReport->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyReport->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Weekly Reports'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="weeklyReports form large-9 medium-8 columns content">
    <?= $this->Form->create($weeklyReport) ?>
    <fieldset>
        <legend><?= __('Edit Weekly Report') ?></legend>
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
