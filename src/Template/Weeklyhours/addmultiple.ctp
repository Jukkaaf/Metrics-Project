<!-- The third page in the weeklyreport form.
     A input is added for all developers managers.
     Pre calculated workinghours are added automatically and if the user
     goes backwards on the page the current values are saved.
-->
<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="weeklyhours form large-4 medium-8 columns content float: left">
    <?= $this->Form->create($weeklyhours) ?>
    <fieldset>
        <legend><?= __('Add Weeklyhours, Page 3/3') ?></legend>
        <?php
            $current_weeklyhours = $this->request->session()->read('current_weeklyhours');
            // if its not the first time the user visits this page in the same report
            // then previous values are loaded
            // else the pre calculated hours are added
            if(!is_null($current_weeklyhours)){
                echo "<tr>";
                for($count = 0; $count < count($memberlist); $count++){
                    print_r($memberlist[$count]['member_name']);
                    echo "<td>";
                    echo $this->Form->input("{$count}.duration", array('value' => $current_weeklyhours[$count]['duration']));
                    echo "</td>";
                }
                echo "</tr>";
            }
            else{
                echo "<tr>";
                for($count = 0; $count < count($memberlist); $count++){
                    print_r($memberlist[$count]['member_name']);
                    echo "<td>";
                    echo $this->Form->input("{$count}.duration", array('value' => $hourlist[$count]));
                    echo "</td>";
                }
                echo "</tr>";
            }
            
        ?>
    </fieldset>
    <?php 
        echo $this->Form->button('Submit', ['name' => 'submit', 'value' => 'submit']);
        echo $this->Form->button('Previous Page', ['name' => 'submit', 'value' => 'previous', 'style' => 'float: left']); 
    ?>
    <?= $this->Form->end() ?>
</div>
