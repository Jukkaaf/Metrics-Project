<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="weeklyreports form large-9 medium-8 columns content">
    <?php echo $this->Form->create(null, ['type' => 'file']); ?>
    <fieldset>
        <legend><?= __('Upload Weeklyreport') ?></legend>
        <?php
            // a form for uploading a new weeklyreport as a txt file
            echo $this->Form->file('uploadfile.', ['single']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>