<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Metric'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="metrics index large-9 medium-8 columns content">
    <h3><?= __('Metrics') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('project_id') ?></th>
                <th><?= $this->Paginator->sort('metric_type') ?></th>
                <th><?= $this->Paginator->sort('date') ?></th>
                <th><?= $this->Paginator->sort('metric_value') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($metrics as $metric): ?>
            <tr>
                <td><?= $this->Number->format($metric->id) ?></td>
                <td><?= $metric->has('project') ? $this->Html->link($metric->project->id, ['controller' => 'Projects', 'action' => 'view', $metric->project->id]) : '' ?></td>
                <td><?= $this->Number->format($metric->metric_type) ?></td>
                <td><?= h($metric->date) ?></td>
                <td><?= $this->Number->format($metric->metric_value) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $metric->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $metric->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $metric->id], ['confirm' => __('Are you sure you want to delete # {0}?', $metric->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
