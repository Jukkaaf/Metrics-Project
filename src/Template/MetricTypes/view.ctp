<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Metric Type'), ['action' => 'edit', $metricType->mtype]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Metric Type'), ['action' => 'delete', $metricType->mtype], ['confirm' => __('Are you sure you want to delete # {0}?', $metricType->mtype)]) ?> </li>
        <li><?= $this->Html->link(__('List Metric Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Metric Type'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="metricTypes view large-9 medium-8 columns content">
    <h3><?= h($metricType->mtype) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($metricType->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Mtype') ?></th>
            <td><?= $this->Number->format($metricType->mtype) ?></td>
        </tr>
    </table>
</div>
