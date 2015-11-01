<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Requirement'), ['action' => 'edit', $requirement->id]) ?> </li>
    </ul>
</nav>
<div class="requirements view large-9 medium-8 columns content">
    <h3><?= h($requirement->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Project') ?></th>
            <td><?= $requirement->has('project') ? $this->Html->link($requirement->project->id, ['controller' => 'Projects', 'action' => 'view', $requirement->project->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($requirement->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($requirement->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($requirement->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Changenum') ?></th>
            <td><?= $this->Number->format($requirement->changenum) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($requirement->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Version') ?></th>
            <td><?= $this->Number->format($requirement->version) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($requirement->date) ?></tr>
        </tr>
    </table>
</div>
