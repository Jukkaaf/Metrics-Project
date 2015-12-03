<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Metric'), ['action' => 'edit', $metric->id]) ?> </li>
    </ul>
</nav>
<div class="metrics view large-9 medium-8 columns content">
    <h3><?= h($metric->metrictype->description) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Weeklyreport') ?></th>
            <td><?= $metric->has('weeklyreport') ? $this->Html->link($metric->weeklyreport->title, ['controller' => 'Meeklyreports', 'action' => 'view', $metric->weeklyreport->id]) : '' ?></td>
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
