<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="members form large-4 medium-8 columns content float: left">
    <?= $this->Form->create($member) ?>
    <fieldset>
        <legend><?= __('Add Member') ?></legend>
        <?php
            echo $this->Form->input('user_id', ['options' => $users]);
            echo $this->Form->input('project_role', 
                ['options' => array('developer' => 'developer', 'manager' => 'manager', 'supervisor' => 'supervisor')]);
            echo $this->Form->input('starting_date', ['empty' => true, 'default' => '']);
            echo $this->Form->input('ending_date', ['empty' => true, 'default' => '']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
