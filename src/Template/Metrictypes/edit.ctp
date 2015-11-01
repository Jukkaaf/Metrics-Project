<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $metrictype->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $metrictype->id)]
            )
        ?></li>
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
