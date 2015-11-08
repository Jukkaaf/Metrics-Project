<?php
$key = isset($key) ? $key : '<%= key %>';
?>
<tr>
    <td>
        <?php echo $this->Form->input("{$key}.member_id") ?>
    </td>
    <td>
        <?php echo $this->Form->input("{$key}.duration"); ?>
    </td>  
    <td class="actions">
        <a href="#" class="remove">Remove Weeklyhours</a>
    </td>
</tr>
