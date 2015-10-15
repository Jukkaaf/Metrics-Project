<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Requirement'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="requirements index large-9 medium-8 columns content">
    <h3><?= __('Requirements') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('changenum') ?></th>
                <th><?= $this->Paginator->sort('project_id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('description') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('version') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requirements as $requirement): ?>
            <tr>
                <td><?= $this->Number->format($requirement->id) ?></td>
                <td><?= $this->Number->format($requirement->changenum) ?></td>
                <td><?= $requirement->has('project') ? $this->Html->link($requirement->project->id, ['controller' => 'Projects', 'action' => 'view', $requirement->project->id]) : '' ?></td>
                <td><?= h($requirement->name) ?></td>
                <td><?= h($requirement->description) ?></td>
                <td><?= $this->Number->format($requirement->status) ?></td>
                <td><?= $this->Number->format($requirement->version) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $requirement->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $requirement->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $requirement->id], ['confirm' => __('Are you sure you want to delete # {0}?', $requirement->id)]) ?>
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
