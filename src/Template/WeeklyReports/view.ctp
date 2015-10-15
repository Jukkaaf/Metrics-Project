<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Weekly Report'), ['action' => 'edit', $weeklyReport->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Weekly Report'), ['action' => 'delete', $weeklyReport->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyReport->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Weekly Reports'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Weekly Report'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="weeklyReports view large-9 medium-8 columns content">
    <h3><?= h($weeklyReport->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Project') ?></th>
            <td><?= $weeklyReport->has('project') ? $this->Html->link($weeklyReport->project->id, ['controller' => 'Projects', 'action' => 'view', $weeklyReport->project->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($weeklyReport->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Reqlink') ?></th>
            <td><?= h($weeklyReport->reqlink) ?></td>
        </tr>
        <tr>
            <th><?= __('Problems') ?></th>
            <td><?= h($weeklyReport->problems) ?></td>
        </tr>
        <tr>
            <th><?= __('Meetings') ?></th>
            <td><?= h($weeklyReport->meetings) ?></td>
        </tr>
        <tr>
            <th><?= __('Additional') ?></th>
            <td><?= h($weeklyReport->additional) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($weeklyReport->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($weeklyReport->date) ?></tr>
        </tr>
    </table>
</div>
