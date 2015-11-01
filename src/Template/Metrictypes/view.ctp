<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Metrictype'), ['action' => 'edit', $metrictype->id]) ?> </li>
    </ul>
</nav>
<div class="metrictypes view large-9 medium-8 columns content">
    <h3><?= h($metrictype->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($metrictype->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($metrictype->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Metrics') ?></h4>
        <?php if (!empty($metrictype->metrics)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Project Id') ?></th>
                <th><?= __('Metrictype Id') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Value') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($metrictype->metrics as $metrics): ?>
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
</div>
