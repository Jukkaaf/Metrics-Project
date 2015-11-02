<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Edit Weeklyreport'), ['action' => 'edit', $weeklyreport->id]) ?> </li>
    </ul>
</nav>
<div class="weeklyreports view large-9 medium-8 columns content">
    <h3><?= h($weeklyreport->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Project') ?></th>
            <td><?= $weeklyreport->has('project') ? $this->Html->link($weeklyreport->project->id, ['controller' => 'Projects', 'action' => 'view', $weeklyreport->project->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($weeklyreport->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Reglink') ?></th>
            <td><?= h($weeklyreport->reglink) ?></td>
        </tr>
        <tr>
            <th><?= __('Problems') ?></th>
            <td><?= h($weeklyreport->problems) ?></td>
        </tr>
        <tr>
            <th><?= __('Meetings') ?></th>
            <td><?= h($weeklyreport->meetings) ?></td>
        </tr>
        <tr>
            <th><?= __('Additional') ?></th>
            <td><?= h($weeklyreport->additional) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($weeklyreport->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Week') ?></th>
            <td><?= h($weeklyreport->week) ?></tr>
        </tr>
        <tr>
            <th><?= __('Created_on') ?></th>
            <td><?= h($weeklyreport->created_on) ?></td>
        </tr>
        <tr>
            <th><?= __('Updated_on') ?></th>
        <td><?= h($weeklyreport->updated_on) ?></td>
    </table>
</div>
