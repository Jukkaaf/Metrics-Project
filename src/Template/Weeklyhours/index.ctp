<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Weeklyhour'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Weeklyreports'), ['controller' => 'Weeklyreports', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Weeklyreport'), ['controller' => 'Weeklyreports', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="weeklyhours index large-9 medium-8 columns content">
    <h3><?= __('Weeklyhours') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('weeklyreport_id') ?></th>
                <th><?= $this->Paginator->sort('member_id') ?></th>
                <th><?= $this->Paginator->sort('duration') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($weeklyhours as $weeklyhour): ?>
            <tr>
                <td><?= $this->Number->format($weeklyhour->id) ?></td>
                <td><?= $weeklyhour->has('weeklyreport') ? $this->Html->link($weeklyhour->weeklyreport->title, ['controller' => 'Weeklyreports', 'action' => 'view', $weeklyhour->weeklyreport->id]) : '' ?></td>
                <td><?= $weeklyhour->has('member') ? $this->Html->link($weeklyhour->member->id, ['controller' => 'Members', 'action' => 'view', $weeklyhour->member->id]) : '' ?></td>
                <td><?= $this->Number->format($weeklyhour->duration) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $weeklyhour->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $weeklyhour->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $weeklyhour->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyhour->id)]) ?>
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
