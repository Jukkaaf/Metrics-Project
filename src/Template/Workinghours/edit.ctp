<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?> </li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $workinghour->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $workinghour->id)]
            )
        ?></li>
    </ul>
</nav>
<div class="workinghours form large-7 medium-8 columns content float: left">
    <?= $this->Form->create($workinghour) ?>
    <fieldset>
        <legend><?= __('Edit Workinghour') ?></legend>
        <?php
            echo $this->Form->input('member_id', ['options' => $members]);
            echo $this->Form->input('date');
            echo $this->Form->input('description');
            echo $this->Form->input('duration');
            echo $this->Form->input('worktype_id', ['options' => $worktypes]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
