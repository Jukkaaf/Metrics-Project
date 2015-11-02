<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Weeklyhour'), ['action' => 'edit', $weeklyhour->id]) ?> </li>
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
