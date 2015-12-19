<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'view', $project->id]) ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $project->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $project->id)]
            )
        ?></li>
    </ul>
</nav>
<div class="projects form large-6 medium-8 columns content float: left">
    <?= $this->Form->create($project) ?>
    <fieldset>
        <legend><?= __('Edit Project') ?></legend>
        <?php
            echo $this->Form->input('project_name');
            //echo $this->Form->input('created_on');
            //echo $this->Form->input('updated_on', ['empty' => true, 'default' => '']);
            echo $this->Form->input('finished_date', ['empty' => true, 'default' => '']);
            echo $this->Form->input('description');
            echo $this->Form->input('is_public');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
