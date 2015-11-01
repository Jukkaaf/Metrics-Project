<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $requirement->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $requirement->id)]
            )
        ?></li>
    </ul>
</nav>
<div class="requirements form large-9 medium-8 columns content">
    <?= $this->Form->create($requirement) ?>
    <fieldset>
        <legend><?= __('Edit Requirement') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('description');
            echo $this->Form->input('status');
            echo $this->Form->input('version');
            echo $this->Form->input('date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
