<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="weeklyreports form large-9 medium-8 columns content">
    <?= $this->Form->create($weeklyreport) ?>
    <fieldset>
        <legend><?= __('Add Weeklyreport, Page 1/3') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('week');
            echo $this->Form->input('reglink');
            echo $this->Form->input('problems');
            echo $this->Form->input('meetings');
            echo $this->Form->input('additional');
            echo $this->Form->input('created_on');
            echo $this->Form->input('updated_on');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Next Page')) ?>
    <?= $this->Form->end() ?>
</div>
