<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="weeklyhours form large-9 medium-8 columns content">
    <?= $this->Form->create($weeklyhours) ?>
    <fieldset>
        <legend><?= __('Add Weeklyhours, Page 3/3') ?></legend>
        <?php
            $current_weeklyhours = $this->request->session()->read('current_weeklyhours');
            echo "<tr>";
            for($count = 0; $count < count($memberlist); $count++){
                print_r($memberlist[$count]['member_name']);
                echo "<td>";
                echo $this->Form->input("{$count}.duration", array('value' => $current_weeklyhours[$count]['duration']));
                echo "</td>";
            }
            echo "</tr>";
        ?>
    </fieldset>
    <?php 
        echo $this->Form->button('Submit', ['name' => 'submit', 'value' => 'submit']);
        echo $this->Form->button('Last Page', ['name' => 'submit', 'value' => 'last']); 
    ?>
    <?= $this->Form->end() ?>
</div>
