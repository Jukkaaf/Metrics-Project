<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Weekly Report'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="weeklyReports index large-9 medium-8 columns content">
    <h3><?= __('Weekly Reports') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('project_id') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('date') ?></th>
                <th><?= $this->Paginator->sort('reqlink') ?></th>
                <th><?= $this->Paginator->sort('problems') ?></th>
                <th><?= $this->Paginator->sort('meetings') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($weeklyReports as $weeklyReport): ?>
            <tr>
                <td><?= $this->Number->format($weeklyReport->id) ?></td>
                <td><?= $weeklyReport->has('project') ? $this->Html->link($weeklyReport->project->id, ['controller' => 'Projects', 'action' => 'view', $weeklyReport->project->id]) : '' ?></td>
                <td><?= h($weeklyReport->title) ?></td>
                <td><?= h($weeklyReport->date) ?></td>
                <td><?= h($weeklyReport->reqlink) ?></td>
                <td><?= h($weeklyReport->problems) ?></td>
                <td><?= h($weeklyReport->meetings) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $weeklyReport->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $weeklyReport->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $weeklyReport->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyReport->id)]) ?>
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
