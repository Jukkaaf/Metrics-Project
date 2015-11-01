<?php
if($this->request->session()->check('selected_project')){
    $selected_project = $this->request->session()->read('selected_project');
    $id = $selected_project['id'];
}
?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['controller' => 'Projects', 'action' => 'view', $id]) ?></li>
        <li><?= $this->Html->link(__('New Weeklyreport'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Upload Weeklyreport'), ['action' => 'upload']) ?></li>
    </ul>
</nav>
<div class="weeklyreports index large-9 medium-8 columns content">
    <h3><?= __('Weeklyreports') ?></h3>
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
            <?php foreach ($weeklyreports as $weeklyreport): ?>
            <tr>
                <td><?= $this->Number->format($weeklyreport->id) ?></td>
                <td><?= $weeklyreport->has('project') ? $this->Html->link($weeklyreport->project->id, ['controller' => 'Projects', 'action' => 'view', $weeklyreport->project->id]) : '' ?></td>
                <td><?= h($weeklyreport->title) ?></td>
                <td><?= h($weeklyreport->date) ?></td>
                <td><?= h($weeklyreport->reqlink) ?></td>
                <td><?= h($weeklyreport->problems) ?></td>
                <td><?= h($weeklyreport->meetings) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $weeklyreport->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $weeklyreport->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $weeklyreport->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyreport->id)]) ?>
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
