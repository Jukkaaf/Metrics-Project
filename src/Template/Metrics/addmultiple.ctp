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
            echo $this->Form->input('phase', array('label' => 'Phase'));
            echo $this->Form->input('reqNew', array('label' => 'Requirement new'));
            echo $this->Form->input('reqInProgress', array('label' => 'Requirement in progress'));
            echo $this->Form->input('reqClosed', array('label' => 'Requirement closed'));
            echo $this->Form->input('reqRejected', array('label' => 'Requirement rejected'));
            echo $this->Form->input('commits', array('label' => 'Commits'));
            echo $this->Form->input('passedTestCases', array('label' => 'Passed test cases'));
            echo $this->Form->input('totalTestCases', array('label' => 'Total test cases'));
        ?>
    </fieldset>
    <?= $this->Form->button(__('Next Page')) ?>
    <?= $this->Form->end() ?>
</div>
