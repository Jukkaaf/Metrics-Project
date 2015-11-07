<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Index'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Project'), ['action' => 'edit', $project->id]) ?> </li>
        <li><?= $this->Html->link(__('Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Metrics'), ['controller' => 'Metrics', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Metrictypes'), ['controller' => 'Metrictypes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Weeklyreports'), ['controller' => 'Weeklyreports', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Weeklyhours'), ['controller' => 'Weeklyhours', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Workinghours'), ['controller' => 'Workinghours', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Log out'), ['controller' => 'Users', 'action' => 'logout']) ?> </li>        
    </ul>
</nav>
<div class="projects view large-9 medium-8 columns content">
    <h3><?= h($project->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Project Name') ?></th>
            <td><?= h($project->project_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= h($project->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($project->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($project->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created On') ?></th>
            <td><?= h($project->created_on->format('Y-m-d')) ?></tr>
        </tr>
        <tr>
            <th><?= __('Updated On') ?></th>
            <td><?= h($project->updated_on) ?></tr>
        </tr>
        <tr>
            <th><?= __('Finished Date') ?></th>
            <td><?= h($project->finished_date) ?></tr>
        </tr>
        <tr>
            <th><?= __('Is Public') ?></th>
            <td><?= $project->is_public ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
</div>
