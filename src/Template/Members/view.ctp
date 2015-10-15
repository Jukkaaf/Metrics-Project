<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Member'), ['action' => 'edit', $member->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Member'), ['action' => 'delete', $member->id], ['confirm' => __('Are you sure you want to delete # {0}?', $member->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Works'), ['controller' => 'Works', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Work'), ['controller' => 'Works', 'action' => 'add']) ?> </li>
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
            <td><?= $this->Number->format($member->project_role) ?></td>
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
        <h4><?= __('Related Works') ?></h4>
        <?php if (!empty($member->works)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Member Id') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Hours') ?></th>
                <th><?= __('Type') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($member->works as $works): ?>
            <tr>
                <td><?= h($works->id) ?></td>
                <td><?= h($works->member_id) ?></td>
                <td><?= h($works->date) ?></td>
                <td><?= h($works->description) ?></td>
                <td><?= h($works->hours) ?></td>
                <td><?= h($works->type) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Works', 'action' => 'view', $works->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Works', 'action' => 'edit', $works->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Works', 'action' => 'delete', $works->id], ['confirm' => __('Are you sure you want to delete # {0}?', $works->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
