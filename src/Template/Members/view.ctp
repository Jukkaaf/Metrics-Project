<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Member'), ['action' => 'edit', $member->id]) ?> </li>
    </ul>
</nav>
<div class="members view large-9 medium-8 columns content">
    <h3><?= h($member->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $member->has('user') ? $this->Html->link($member->user->id, ['controller' => 'Users', 'action' => 'view', $member->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Project') ?></th>
            <td><?= $member->has('project') ? $this->Html->link($member->project->id, ['controller' => 'Projects', 'action' => 'view', $member->project->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($member->id) ?></td>
        </tr>
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
    </table>
    <div class="related">
        <h4><?= __('Related Workinghours') ?></h4>
        <?php if (!empty($member->workinghours)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Member Id') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Duration') ?></th>
                <th><?= __('Worktype') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($member->workinghours as $workinghours): ?>
            <tr>
                <td><?= h($workinghours->id) ?></td>
                <td><?= h($workinghours->member_id) ?></td>
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
