<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Workinghour'), ['action' => 'edit', $workinghour->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Workinghour'), ['action' => 'delete', $workinghour->id], ['confirm' => __('Are you sure you want to delete # {0}?', $workinghour->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Workinghours'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Workinghour'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="workinghours view large-9 medium-8 columns content">
    <h3><?= h($workinghour->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $workinghour->has('member') ? $this->Html->link($workinghour->member->id, ['controller' => 'Members', 'action' => 'view', $workinghour->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($workinghour->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($workinghour->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Duration') ?></th>
            <td><?= $this->Number->format($workinghour->duration) ?></td>
        </tr>
        <tr>
            <th><?= __('Worktype') ?></th>
            <td><?= $this->Number->format($workinghour->worktype) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($workinghour->date) ?></tr>
        </tr>
    </table>
</div>
