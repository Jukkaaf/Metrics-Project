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
        <li><?= $this->Html->link(__('Charts'), ['controller' => 'Charts', 'action' => 'index']) ?></li>
        <!--<li><?= $this->Html->link(__('Upload Weeklyreport'), ['action' => 'upload']) ?></li>-->
    </ul>
</nav>
<div class="weeklyreports index large-9 medium-8 columns content">
    <h3><?= __('Weeklyreports') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('week') ?></th>
                <th><?= $this->Paginator->sort('year') ?></th>
                <th><?= $this->Paginator->sort('created_on') ?></th>
                <th><?= $this->Paginator->sort('updated_on') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($weeklyreports as $weeklyreport): ?>
            <tr>
                <td><?= h($weeklyreport->title) ?></td>
                <td><?= h($weeklyreport->week) ?></td>
                <td><?= h($weeklyreport->year) ?></td>
                <td><?= h($weeklyreport->created_on->format('Y-m-d')) ?></td>
                <td><?= h($weeklyreport->updated_on) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Select'), ['action' => 'view', $weeklyreport->id]) ?>
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
