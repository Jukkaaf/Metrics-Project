<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $weeklyreport->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyreport->id)]
            )
        ?></li>
    </ul>
</nav>
<div class="weeklyreports form large-5 medium-8 columns content float: left">
    <?= $this->Form->create($weeklyreport) ?>
    <fieldset>
        <legend><?= __('Edit Weeklyreport') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('week');
            echo $this->Form->input('year');
            echo $this->Form->input('reglink');
            echo $this->Form->input('problems');
            echo $this->Form->input('meetings');
            echo $this->Form->input('additional');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
