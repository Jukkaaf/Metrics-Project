<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $metricType->mtype],
                ['confirm' => __('Are you sure you want to delete # {0}?', $metricType->mtype)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Metric Types'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="metricTypes form large-9 medium-8 columns content">
    <?= $this->Form->create($metricType) ?>
    <fieldset>
        <legend><?= __('Edit Metric Type') ?></legend>
        <?php
            echo $this->Form->input('description');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
