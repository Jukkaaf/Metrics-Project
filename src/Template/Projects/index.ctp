<nav class="large-2 medium-4 columns" id="actions-sidebar">    
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <?php
            $admin = $this->request->session()->read('is_admin');
            $supervisor = $this->request->session()->read('is_supervisor');
            if($admin){
        ?>
            <li><?= $this->Html->link(__('New Project'), ['action' => 'add']) ?></li>
            <li><?= $this->Html->link(__('Manage Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Metrictypes'), ['controller' => 'Metrictypes', 'action' => 'index']) ?> </li>
            <li><?= $this->Html->link(__('Worktypes'), ['controller' => 'Worktypes', 'action' => 'index']) ?> </li>
        <?php
            }
            if(!$admin && $supervisor){
        ?>
            <li><?= $this->Html->link(__('New Project'), ['action' => 'add']) ?></li>
        <?php
            }
        ?>
        <li><?= $this->Html->link(__('Public statistics'), ['controller' => 'Projects', 'action' => 'statistics']) ?> </li>
        <li><?= $this->Html->link(__('FAQ'), ['controller' => 'Projects', 'action' => 'faq']) ?> </li>
    </ul>    
</nav>
<div class="projects index large-8 medium-8 columns content float: left">
    <!-- List of the projects the user is a member of-->
    <?php if($this->request->session()->check('Auth.User')){ ?>
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
                    <?php if(in_array($project->id, $this->request->session()->read('project_memberof_list'))){ ?>
                        <tr>
                            <td><?= h($project->project_name) ?></td>
                            <td><?= h($project->description) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('Select'), ['action' => 'view', $project->id]) ?>
                            </td>
                        </tr>       
                    <?php } ?>
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
    <?php }?>
    
    <!-- List of the public projects-->
    <div>
        <h3><?= __('Public projects') ?></h3>
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
                    <?php if($project->is_public){ ?>
                        <tr>
                            <td><?= h($project->project_name) ?></td>
                            <td><?= h($project->description) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('Select'), ['action' => 'view', $project->id]) ?>
                            </td>
                        </tr>
                    <?php } ?>
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
</div>
