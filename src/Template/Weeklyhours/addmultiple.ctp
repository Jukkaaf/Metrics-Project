<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="weeklyhours form large-9 medium-8 columns content">
    <?= $this->Form->create($weeklyhour) ?>
    <fieldset>
        <legend><?= __('Add Weeklyhours, Page 3/3') ?></legend>
        <?php
            echo $this->Form->input('member_id', ['options' => $members]);
            echo $this->Form->input('duration');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Finish')) ?>
    <?= $this->Form->end() ?>
</div>
