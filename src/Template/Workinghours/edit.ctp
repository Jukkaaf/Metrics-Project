<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $workinghour->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $workinghour->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Workinghours'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="workinghours form large-9 medium-8 columns content">
    <?= $this->Form->create($workinghour) ?>
    <fieldset>
        <legend><?= __('Edit Workinghour') ?></legend>
        <?php
            echo $this->Form->input('member_id', ['options' => $members]);
            echo $this->Form->input('date');
            echo $this->Form->input('description');
            echo $this->Form->input('duration');
            echo $this->Form->input('worktype');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
