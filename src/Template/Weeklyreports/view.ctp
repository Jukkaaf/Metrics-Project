<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Edit Weeklyreport'), ['action' => 'edit', $weeklyreport->id]) ?> </li>
    </ul>
</nav>
<div class="weeklyreports view large-6 medium-8 columns content float: left">
    <h3><?= h($weeklyreport->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($weeklyreport->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Week') ?></th>
            <td><?= h($weeklyreport->week) ?></tr>
        </tr>
        <tr>
            <th><?= __('Year') ?></th>
            <td><?= h($weeklyreport->year) ?></tr>
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
            <th><?= __('Created on') ?></th>
            <td><?= h($weeklyreport->created_on->format('Y-m-d')) ?></td>
        </tr>
        <tr>
            <th><?= __('Updated on') ?></th>
        <td><?= h($weeklyreport->updated_on) ?></td>
    </table>
    <div class="related">
        <h4><?= __('Related Weeklyhours') ?></h4>
            <?php if (!empty($weeklyreport->weeklyhours)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?= __('Name / project role') ?></th>
                    <th><?= __('Duration') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($weeklyreport->weeklyhours as $weeklyhours): ?>
                <tr>
                    <td><?= h($weeklyhours->member_name) ?></td>
                    <td><?= h($weeklyhours->duration) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Edit'), ['controller' => 'Weeklyhours', 'action' => 'edit', $weeklyhours->id]) ?>

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
                    <td><?= h($metrics->metric_description) ?></td>
                    <td><?= h($metrics->date->format('Y-m-d')) ?></td>
                    <td><?= h($metrics->value) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Edit'), ['controller' => 'Metrics', 'action' => 'edit', $metrics->id]) ?>

                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
