<nav class="large-3 medium-4 columns" id="actions-sidebar">    
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Project'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Log in'), ['controller' => 'Users', 'action' => 'login']) ?> </li>
        <li><?= $this->Html->link(__('Sign up'), ['controller' => 'Users', 'action' => 'signup']) ?></li>
        <li><?= $this->Html->link(__('Edit Profile'), ['controller' => 'Users', 'action' => 'editprofile']) ?></li>
        <li><?= $this->Html->link(__('Manage Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Metrictypes'), ['controller' => 'Metrictypes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Worktypes'), ['controller' => 'Worktypes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Log out'), ['controller' => 'Users', 'action' => 'logout']) ?> </li>
    </ul>    
</nav>
<div class="projects index large-9 medium-8 columns content">
    <h3><?= __('Projects') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('project_name') ?></th>
                <th><?= $this->Paginator->sort('description') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project): ?>
            <tr>
                <td><?= h($project->project_name) ?></td>
                <td><?= h($project->description) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Select'), ['action' => 'view', $project->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
