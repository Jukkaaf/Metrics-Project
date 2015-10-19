<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Workinghour'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="workinghours index large-9 medium-8 columns content">
    <h3><?= __('Workinghours') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('member_id') ?></th>
                <th><?= $this->Paginator->sort('date') ?></th>
                <th><?= $this->Paginator->sort('description') ?></th>
                <th><?= $this->Paginator->sort('duration') ?></th>
                <th><?= $this->Paginator->sort('worktype') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workinghours as $workinghour): ?>
            <tr>
                <td><?= $this->Number->format($workinghour->id) ?></td>
                <td><?= $workinghour->has('member') ? $this->Html->link($workinghour->member->id, ['controller' => 'Members', 'action' => 'view', $workinghour->member->id]) : '' ?></td>
                <td><?= h($workinghour->date) ?></td>
                <td><?= h($workinghour->description) ?></td>
                <td><?= $this->Number->format($workinghour->duration) ?></td>
                <td><?= $this->Number->format($workinghour->worktype) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $workinghour->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $workinghour->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $workinghour->id], ['confirm' => __('Are you sure you want to delete # {0}?', $workinghour->id)]) ?>
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
