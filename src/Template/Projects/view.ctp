<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Project'), ['action' => 'edit', $project->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Project'), ['action' => 'delete', $project->id], ['confirm' => __('Are you sure you want to delete # {0}?', $project->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Projects'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Metrics'), ['controller' => 'Metrics', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Metric'), ['controller' => 'Metrics', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Requirements'), ['controller' => 'Requirements', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Requirement'), ['controller' => 'Requirements', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Weekly Reports'), ['controller' => 'WeeklyReports', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Weekly Report'), ['controller' => 'WeeklyReports', 'action' => 'add']) ?> </li>
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
                <th><?= __('Metric Type') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Metric Value') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($project->metrics as $metrics): ?>
            <tr>
                <td><?= h($metrics->id) ?></td>
                <td><?= h($metrics->project_id) ?></td>
                <td><?= h($metrics->metric_type) ?></td>
                <td><?= h($metrics->date) ?></td>
                <td><?= h($metrics->metric_value) ?></td>
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
                <th><?= __('Change Id') ?></th>
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
                <td><?= h($requirements->change_id) ?></td>
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
        <h4><?= __('Related Weekly Reports') ?></h4>
        <?php if (!empty($project->weekly_reports)): ?>
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
            <?php foreach ($project->weekly_reports as $weeklyReports): ?>
            <tr>
                <td><?= h($weeklyReports->id) ?></td>
                <td><?= h($weeklyReports->project_id) ?></td>
                <td><?= h($weeklyReports->title) ?></td>
                <td><?= h($weeklyReports->date) ?></td>
                <td><?= h($weeklyReports->reqlink) ?></td>
                <td><?= h($weeklyReports->problems) ?></td>
                <td><?= h($weeklyReports->meetings) ?></td>
                <td><?= h($weeklyReports->additional) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'WeeklyReports', 'action' => 'view', $weeklyReports->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'WeeklyReports', 'action' => 'edit', $weeklyReports->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'WeeklyReports', 'action' => 'delete', $weeklyReports->id], ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyReports->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
