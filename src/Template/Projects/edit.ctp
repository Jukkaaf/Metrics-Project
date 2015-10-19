<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $project->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $project->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Metrics'), ['controller' => 'Metrics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Metric'), ['controller' => 'Metrics', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Requirements'), ['controller' => 'Requirements', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Requirement'), ['controller' => 'Requirements', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Weeklyreports'), ['controller' => 'Weeklyreports', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Weeklyreport'), ['controller' => 'Weeklyreports', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="projects form large-9 medium-8 columns content">
    <?= $this->Form->create($project) ?>
    <fieldset>
        <legend><?= __('Edit Project') ?></legend>
        <?php
            echo $this->Form->input('project_name');
            echo $this->Form->input('created_on');
            echo $this->Form->input('updated_on', ['empty' => true, 'default' => '']);
            echo $this->Form->input('finished_date', ['empty' => true, 'default' => '']);
            echo $this->Form->input('status');
            echo $this->Form->input('description');
            echo $this->Form->input('is_public');
            echo $this->Form->input('importance');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
