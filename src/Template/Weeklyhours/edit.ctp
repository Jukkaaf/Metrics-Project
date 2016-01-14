<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <?php
            $admin = $this->request->session()->read('is_admin');
            if($admin){
        ?>
            <li><?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $weeklyhour->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $weeklyhour->id)]
                )
            ?></li>
        <?php
            }
        ?> 
    </ul>
</nav>
<div class="weeklyhours form large-5 medium-8 columns content float: left">
    <?= $this->Form->create($weeklyhour) ?>
    <fieldset>
        <legend><?= __('Edit Weeklyhour') ?></legend>
        <?php
            echo $this->Form->input('member_id', ['options' => $members]);
            echo $this->Form->input('duration');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
