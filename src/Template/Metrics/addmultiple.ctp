<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="metrics form large-9 medium-8 columns content">
    <?= $this->Form->create($metric) ?>
    <fieldset>
        <legend><?= __('Add Metrics, Page 2/3') ?></legend>
        <?php            
            echo $this->Form->input('phase', array('label' => 'Phase', 'type' => 'number', 'required' => true));
            echo $this->Form->input('reqNew', array('label' => 'Requirement new', 'type' => 'number', 'required' => true));
            echo $this->Form->input('reqInProgress', array('label' => 'Requirement in progress', 'type' => 'number', 'required' => true));
            echo $this->Form->input('reqClosed', array('label' => 'Requirement closed', 'type' => 'number', 'required' => true));
            echo $this->Form->input('reqRejected', array('label' => 'Requirement rejected', 'type' => 'number', 'required' => true));
            echo $this->Form->input('commits', array('label' => 'Commits', 'type' => 'number'));
            echo $this->Form->input('passedTestCases', array('label' => 'Passed test cases', 'type' => 'number', 'required' => true));
            echo $this->Form->input('totalTestCases', array('label' => 'Total test cases', 'type' => 'number', 'required' => true));
        ?>
    </fieldset>
    <?= $this->Form->button(__('Next Page')) ?>
    <?= $this->Form->end() ?>
</div>
