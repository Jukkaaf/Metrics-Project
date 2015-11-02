<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Weeklyhour'), ['action' => 'edit', $weeklyhour->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Weeklyhour'), ['action' => 'delete', $weeklyhour->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyhour->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Weeklyhours'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Weeklyhour'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Weeklyreports'), ['controller' => 'Weeklyreports', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Weeklyreport'), ['controller' => 'Weeklyreports', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="weeklyhours view large-9 medium-8 columns content">
    <h3><?= h($weeklyhour->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Weeklyreport') ?></th>
            <td><?= $weeklyhour->has('weeklyreport') ? $this->Html->link($weeklyhour->weeklyreport->title, ['controller' => 'Weeklyreports', 'action' => 'view', $weeklyhour->weeklyreport->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $weeklyhour->has('member') ? $this->Html->link($weeklyhour->member->id, ['controller' => 'Members', 'action' => 'view', $weeklyhour->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($weeklyhour->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Duration') ?></th>
            <td><?= $this->Number->format($weeklyhour->duration) ?></td>
        </tr>
    </table>
</div>
