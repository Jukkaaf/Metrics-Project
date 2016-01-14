<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $metric->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $metric->id)]
            )
        ?></li>
        <?php
            $admin = $this->request->session()->read('is_admin');
            if($admin){
        ?>
            <li><?= $this->Form->postLink(
                        __('Delete admin'),
                    ['action' => 'deleteadmin', $metric->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $metric->id)]
                )
            ?></li>
        <?php
            }
        ?> 
    </ul>
</nav>
<div class="metrics form large-4 medium-8 columns content float: left">
    <?= $this->Form->create($metric) ?>
    <fieldset>
        <legend><?= __('Edit Metric') ?></legend>
        <?php
            echo $this->Form->input('value');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
