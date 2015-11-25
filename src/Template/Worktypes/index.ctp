<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Worktype'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Workinghours'), ['controller' => 'Workinghours', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Workinghour'), ['controller' => 'Workinghours', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="worktypes index large-9 medium-8 columns content">
    <h3><?= __('Worktypes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('description') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($worktypes as $worktype): ?>
            <tr>
                <td><?= $this->Number->format($worktype->id) ?></td>
                <td><?= h($worktype->description) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $worktype->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $worktype->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $worktype->id], ['confirm' => __('Are you sure you want to delete # {0}?', $worktype->id)]) ?>
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
