<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Work'), ['action' => 'edit', $work->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Work'), ['action' => 'delete', $work->id], ['confirm' => __('Are you sure you want to delete # {0}?', $work->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Works'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Work'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="works view large-9 medium-8 columns content">
    <h3><?= h($work->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $work->has('member') ? $this->Html->link($work->member->id, ['controller' => 'Members', 'action' => 'view', $work->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($work->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($work->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Hours') ?></th>
            <td><?= $this->Number->format($work->hours) ?></td>
        </tr>
        <tr>
            <th><?= __('Type') ?></th>
            <td><?= $this->Number->format($work->type) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($work->date) ?></tr>
        </tr>
    </table>
</div>
