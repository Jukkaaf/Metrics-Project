<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('First Name') ?></th>
            <td><?= h($user->first_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Last Name') ?></th>
            <td><?= h($user->last_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Phone') ?></th>
            <td><?= h($user->phone) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Role') ?></th>
            <td><?= h($user->role) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Members') ?></h4>
        <?php if (!empty($user->members)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('User Id') ?></th>
                <th><?= __('Project Id') ?></th>
                <th><?= __('Project Role') ?></th>
                <th><?= __('Starting Date') ?></th>
                <th><?= __('Ending Date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->members as $members): ?>
            <tr>
                <td><?= h($members->id) ?></td>
                <td><?= h($members->user_id) ?></td>
                <td><?= h($members->project_id) ?></td>
                <td><?= h($members->project_role) ?></td>
                <td><?= h($members->starting_date) ?></td>
                <td><?= h($members->ending_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Members', 'action' => 'view', $members->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Members', 'action' => 'edit', $members->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Members', 'action' => 'delete', $members->id], ['confirm' => __('Are you sure you want to delete # {0}?', $members->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
