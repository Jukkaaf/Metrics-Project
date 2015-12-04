<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['controller' => 'Worktypes', 'action' => 'index']) ?></li>
    </ul>
</nav>
<div class="worktypes form large-9 medium-8 columns content">
    <?= $this->Form->create($worktype) ?>
    <fieldset>
        <legend><?= __('Add Worktype') ?></legend>
        <?php
            echo $this->Form->input('description');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
