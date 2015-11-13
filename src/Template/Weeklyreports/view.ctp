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
    <div class="related">
        <h4><?= __('Related Weeklyhours') ?></h4>
            <?php if (!empty($weeklyreport->weeklyhours)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?= __('Member Id') ?></th>
                    <th><?= __('Duration') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($weeklyreport->weeklyhours as $weeklyhours): ?>
                <tr>
                    <td><?= h($weeklyhours->member_id) ?></td>
                    <td><?= h($weeklyhours->duration) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['controller' => 'Meeklyhours', 'action' => 'view', $weeklyhours->id]) ?>

                        <?= $this->Html->link(__('Edit'), ['controller' => 'Meeklyhours', 'action' => 'edit', $weeklyhours->id]) ?>

                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'Meeklyhours', 'action' => 'delete', $weeklyhours->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyhours->id)]) ?>

                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <h4><?= __('Related Metrics') ?></h4>
            <?php if (!empty($weeklyreport->metrics)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?= __('Metrictype Id') ?></th>
                    <th><?= __('Date') ?></th>
                    <th><?= __('Value') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($weeklyreport->metrics as $metrics): ?>
                <tr>
                    <td><?= h($metrics->metrictype_id) ?></td>
                    <td><?= h($metrics->date) ?></td>
                    <td><?= h($metrics->value) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['controller' => 'Metrics', 'action' => 'view', $metrics->id]) ?>

                        <?= $this->Html->link(__('Edit'), ['controller' => 'Metrics', 'action' => 'edit', $metrics->id]) ?>

                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'Metrics', 'action' => 'delete', $metrics->id], ['confirm' => __('Are you sure you want to delete # {0}?', $metrics->id)]) ?>

                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
