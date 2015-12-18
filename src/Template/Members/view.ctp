<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Member'), ['action' => 'edit', $member->id]) ?> </li>
    </ul>
</nav>
<div class="members view large-6 medium-8 columns content float: left">
    <h3><?= h($member->user->first_name . " ". $member->user->last_name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Project Role') ?></th>
            <td><?= h($member->project_role) ?></td>
        </tr>
        <tr>
            <th><?= __('Starting Date') ?></th>
            <td><?= h($member->starting_date) ?></tr>
        </tr>
        <tr>
            <th><?= __('Ending Date') ?></th>
            <td><?= h($member->ending_date) ?></tr>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= $member->has('user') ? $this->Html->link($member->user->email, ['controller' => 'Users', 'action' => 'view', $member->user->id]) : '' ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Weeklyhours') ?></h4>
        <?php if (!empty($member->weeklyhours)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?= __('Weeklyreport id') ?></th>
                    <th><?= __('Duration') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($member->weeklyhours as $weeklyhours): ?>
                <tr>
                    <td><?= h($weeklyhours->weeklyreport_id) ?></td>
                    <td><?= h($weeklyhours->duration) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['controller' => 'Weeklyhours', 'action' => 'view', $weeklyhours->id]) ?>

                        <?= $this->Html->link(__('Edit'), ['controller' => 'Weeklyhours', 'action' => 'edit', $weeklyhours->id]) ?>

                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'Weeklyhours', 'action' => 'delete', $weeklyhours->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyhours->id)]) ?>

                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <h4><?= __('Related Workinghours') ?></h4>
        <?php if (!empty($member->workinghours)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?= __('Date') ?></th>
                    <th><?= __('Description') ?></th>
                    <th><?= __('Duration') ?></th>
                    <th><?= __('Worktype') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($member->workinghours as $workinghours): ?>
                <tr>
                    <td><?= h($workinghours->date) ?></td>
                    <td><?= h($workinghours->description) ?></td>
                    <td><?= h($workinghours->duration) ?></td>
                    <td><?= h($workinghours->worktype) ?></td>
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
