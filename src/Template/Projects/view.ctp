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
            <td><?= h($project->created_on) ?></tr>
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
    <div class="related">
        <h4><?= __('Related Members') ?></h4>
        <?php if (!empty($project->members)): ?>
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
            <?php foreach ($project->members as $members): ?>
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
    <div class="related">
        <h4><?= __('Related Metrics') ?></h4>
        <?php if (!empty($project->metrics)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Project Id') ?></th>
                <th><?= __('Metrictype Id') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Value') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($project->metrics as $metrics): ?>
            <tr>
                <td><?= h($metrics->id) ?></td>
                <td><?= h($metrics->project_id) ?></td>
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
    <div class="related">
        <h4><?= __('Related Requirements') ?></h4>
        <?php if (!empty($project->requirements)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Changenum') ?></th>
                <th><?= __('Project Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Version') ?></th>
                <th><?= __('Date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($project->requirements as $requirements): ?>
            <tr>
                <td><?= h($requirements->id) ?></td>
                <td><?= h($requirements->changenum) ?></td>
                <td><?= h($requirements->project_id) ?></td>
                <td><?= h($requirements->name) ?></td>
                <td><?= h($requirements->description) ?></td>
                <td><?= h($requirements->status) ?></td>
                <td><?= h($requirements->version) ?></td>
                <td><?= h($requirements->date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Requirements', 'action' => 'view', $requirements->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Requirements', 'action' => 'edit', $requirements->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Requirements', 'action' => 'delete', $requirements->id], ['confirm' => __('Are you sure you want to delete # {0}?', $requirements->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Weeklyreports') ?></h4>
        <?php if (!empty($project->weeklyreports)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Project Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Reqlink') ?></th>
                <th><?= __('Problems') ?></th>
                <th><?= __('Meetings') ?></th>
                <th><?= __('Additional') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($project->weeklyreports as $weeklyreports): ?>
            <tr>
                <td><?= h($weeklyreports->id) ?></td>
                <td><?= h($weeklyreports->project_id) ?></td>
                <td><?= h($weeklyreports->title) ?></td>
                <td><?= h($weeklyreports->date) ?></td>
                <td><?= h($weeklyreports->reqlink) ?></td>
                <td><?= h($weeklyreports->problems) ?></td>
                <td><?= h($weeklyreports->meetings) ?></td>
                <td><?= h($weeklyreports->additional) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Weeklyreports', 'action' => 'view', $weeklyreports->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Weeklyreports', 'action' => 'edit', $weeklyreports->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Weeklyreports', 'action' => 'delete', $weeklyreports->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyreports->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
