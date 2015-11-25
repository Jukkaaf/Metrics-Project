<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Worktype'), ['action' => 'edit', $worktype->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Worktype'), ['action' => 'delete', $worktype->id], ['confirm' => __('Are you sure you want to delete # {0}?', $worktype->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Worktypes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Worktype'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Workinghours'), ['controller' => 'Workinghours', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Workinghour'), ['controller' => 'Workinghours', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="worktypes view large-9 medium-8 columns content">
    <h3><?= h($worktype->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($worktype->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($worktype->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Workinghours') ?></h4>
        <?php if (!empty($worktype->workinghours)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Member Id') ?></th>
                <th><?= __('Worktype Id') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Duration') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($worktype->workinghours as $workinghours): ?>
            <tr>
                <td><?= h($workinghours->id) ?></td>
                <td><?= h($workinghours->member_id) ?></td>
                <td><?= h($workinghours->worktype_id) ?></td>
                <td><?= h($workinghours->date) ?></td>
                <td><?= h($workinghours->description) ?></td>
                <td><?= h($workinghours->duration) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Workinghours', 'action' => 'view', $workinghours->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Workinghours', 'action' => 'edit', $workinghours->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Workinghours', 'action' => 'delete', $workinghours->id], ['confirm' => __('Are you sure you want to delete # {0}?', $workinghours->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
