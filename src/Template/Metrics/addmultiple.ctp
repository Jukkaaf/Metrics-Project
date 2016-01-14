<!-- This is the second page in the weeklyreport form.
     $current_metrics is what was previously placed in the form if the user visits this page a second time
-->
<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="metrics form large-4 medium-8 columns content float: left">
    <?= $this->Form->create($metric) ?>
    <fieldset>
        <legend><?= __('Add Metrics, Page 2/3') ?></legend>
        <?php
            $current_metrics = $this->request->session()->read('current_metrics');
        
            echo $this->Form->input('phase', 
                array('value' => $current_metrics[0]['value'], 'label' => 'Phase', 
                'type' => 'number', 'required' => true, 'min' => 0));
            echo $this->Form->input('totalPhases', 
                array('value' => $current_metrics[1]['value'], 'label' => 'Total Phases', 
                'type' => 'number', 'required' => true, 'min' => 0));
            echo $this->Form->input('reqNew', 
                array('value' => $current_metrics[2]['value'], 'label' => 'Requirement new', 
                'type' => 'number', 'required' => true, 'min' => 0));
            echo $this->Form->input('reqInProgress', 
                array('value' => $current_metrics[3]['value'], 'label' => 'Requirement in progress', 
                'type' => 'number', 'required' => true, 'min' => 0));
            echo $this->Form->input('reqClosed', 
                array('value' => $current_metrics[4]['value'], 'label' => 'Requirement closed', 
                'type' => 'number', 'required' => true, 'min' => 0));
            echo $this->Form->input('reqRejected', 
                array('value' => $current_metrics[5]['value'], 'label' => 'Requirement rejected', 
                'type' => 'number', 'required' => true, 'min' => 0));
            echo $this->Form->input('commits', 
                array('value' => $current_metrics[6]['value'], 'label' => 'Commits', 
                'type' => 'number', 'required' => true, 'min' => 0));
            echo $this->Form->input('passedTestCases', 
                array('value' => $current_metrics[7]['value'], 'label' => 'Passed test cases', 
                'type' => 'number', 'required' => true, 'min' => 0));
            echo $this->Form->input('totalTestCases', 
                array('value' => $current_metrics[8]['value'], 'label' => 'Total test cases', 
                'type' => 'number', 'required' => true, 'min' => 0));
        ?>
    </fieldset>
    <?php 
        echo $this->Form->button('Next Page', ['name' => 'submit', 'value' => 'next']);
        echo $this->Form->button('Previous Page', ['name' => 'submit', 'value' => 'previous', 'style' => 'float: left']); 
    ?>
    <?= $this->Form->end() ?>
</div>
