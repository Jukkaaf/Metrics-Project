<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Metric'), ['action' => 'edit', $metric->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Metric'), ['action' => 'delete', $metric->id], ['confirm' => __('Are you sure you want to delete # {0}?', $metric->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Metrics'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Metric'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="metrics view large-9 medium-8 columns content">
    <h3><?= h($metric->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Project') ?></th>
            <td><?= $metric->has('project') ? $this->Html->link($metric->project->id, ['controller' => 'Projects', 'action' => 'view', $metric->project->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($metric->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Metric Type') ?></th>
            <td><?= $this->Number->format($metric->metric_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Metric Value') ?></th>
            <td><?= $this->Number->format($metric->metric_value) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($metric->date) ?></tr>
        </tr>
    </table>
</div>
