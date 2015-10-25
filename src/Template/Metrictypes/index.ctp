<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Metrictype'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Metrics'), ['controller' => 'Metrics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Metric'), ['controller' => 'Metrics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="metrictypes index large-9 medium-8 columns content">
    <h3><?= __('Metrictypes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('description') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($metrictypes as $metrictype): ?>
            <tr>
                <td><?= $this->Number->format($metrictype->id) ?></td>
                <td><?= h($metrictype->description) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $metrictype->mtype]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $metrictype->mtype]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $metrictype->mtype], ['confirm' => __('Are you sure you want to delete # {0}?', $metrictype->mtype)]) ?>
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
