<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Metric'), ['action' => 'edit', $metric->id]) ?> </li>
    </ul>
</nav>
<div class="metrics view large-9 medium-8 columns content">
    <h3><?= h($metric->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Project') ?></th>
            <td><?= $metric->has('project') ? $this->Html->link($metric->project->id, ['controller' => 'Projects', 'action' => 'view', $metric->project->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Metrictype') ?></th>
            <td><?= $metric->has('metrictype') ? $this->Html->link($metric->metrictype->id, ['controller' => 'Metrictypes', 'action' => 'view', $metric->metrictype->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($metric->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Value') ?></th>
            <td><?= $this->Number->format($metric->value) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($metric->date) ?></tr>
        </tr>
    </table>
</div>
