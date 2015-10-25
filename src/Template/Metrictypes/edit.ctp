<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $metrictype->mtype],
                ['confirm' => __('Are you sure you want to delete # {0}?', $metrictype->mtype)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Metrictypes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Metrics'), ['controller' => 'Metrics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Metric'), ['controller' => 'Metrics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="metrictypes form large-9 medium-8 columns content">
    <?= $this->Form->create($metrictype) ?>
    <fieldset>
        <legend><?= __('Edit Metrictype') ?></legend>
        <?php
            echo $this->Form->input('id');
            echo $this->Form->input('description');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
