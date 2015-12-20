<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Metric'), ['action' => 'add']) ?></li>
        <?php
            $admin = $this->request->session()->read('is_admin');
            if($admin){
        ?>
            <li><?= $this->Html->link(__('New Metric admin'), ['action' => 'addadmin']) ?></li>
        <?php
            }
        ?> 
    </ul>
</nav>
<div class="metrics index large-7 medium-8 columns content float: left">
    <h3><?= __('Metrics') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('metrictype_id') ?></th>
                <th><?= $this->Paginator->sort('value') ?></th>
                <th><?= $this->Paginator->sort('weeklyreport_id') ?></th>
                <th><?= $this->Paginator->sort('date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($metrics as $metric): ?>
            <tr>
                <td><?= $metric->has('metrictype') ? $this->Html->link($metric->metrictype->description, ['controller' => 'Metrictypes', 'action' => 'view', $metric->metrictype->id]) : '' ?></td>
                <td><?= $this->Number->format($metric->value) ?></td>
                <td><?= $metric->has('weeklyreport') ? $this->Html->link($metric->weeklyreport->title, ['controller' => 'Weeklyreports', 'action' => 'view', $metric->weeklyreport->id]) : '' ?></td>
                <td><?= h($metric->date->format('Y-m-d')) ?></td>
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
