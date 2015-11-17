<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
    </ul>
</nav>
<div class="weeklyhours form large-9 medium-8 columns content">
    <?= $this->Form->create($weeklyhours) ?>
    <!--
    <fieldset>
        <legend><?= __('Add Weeklyhours, Page 3/3') ?></legend>
        <?php
            echo $this->Form->input('member_id', ['options' => $members]);
            echo $this->Form->input('duration');
        ?>
    </fieldset>
    -->
    <fieldset>
        <legend><?php echo __('Weeklyhours');?></legend>
        <table id="weeklyhours-table">
            <tbody>
                <?php if (!empty($subject->weeklyhours)) :?>
                    <?php for ($key = 0; $key < count($subject->weeklyhours); $key++) :?>
                        <?php echo $this->element('weeklyhours', array('key' => $key));?>
                    <?php endfor;?>
                <?php endif;?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td class="actions">
                        <a href="#" class="add">Add weeklyhours</a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </fieldset>
    
    <script id="weeklyhours-template" type="text/x-underscore-template">
        <?php echo $this->element('weeklyhours');?>
    </script>

    <?= $this->Form->button(__('Finish')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
$(document).ready(function() {
    var
        weeklyhoursTable = $('#weeklyhours-table'),
        weeklyhoursBody = weeklyhoursTable.find('tbody'),
        weeklyhoursTemplate = _.template($('#weeklyhours-template').remove().text()),
        numberRows = weeklyhoursTable.find('tbody > tr').length;

    weeklyhoursTable
        .on('click', 'a.add', function(e) {
            e.preventDefault();

            $(weeklyhoursTemplate({key: numberRows++}))
                .hide()
                .appendTo(weeklyhoursBody)
                .fadeIn('fast');
        })
        .on('click', 'a.remove', function(e) {
                e.preventDefault();

            $(this)
                .closest('tr')
                .fadeOut('fast', function() {
                    $(this).remove();
                });
        });

        if (numberRows === 0) {
            weeklyhoursTable.find('a.add').click();
        }
});
</script>