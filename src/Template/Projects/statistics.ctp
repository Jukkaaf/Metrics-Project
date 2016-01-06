<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
            <?= $this->Form->create() ?>
            <fieldset>
                <legend><?= __('Edit limits') ?></legend>
                <?php
                    echo $this->Form->input('weekmin', array('type' => 'number', 'value' => $this->request->session()->read('statistics_limits')['weekmin']));
                    echo $this->Form->input('weekmax', array('type' => 'number', 'value' => $this->request->session()->read('statistics_limits')['weekmax']));
                    echo $this->Form->input('year', array('type' => 'number', 'value' => $this->request->session()->read('statistics_limits')['year']));
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
    </ul>
</nav>
<div class="projects view large-9 medium-8 columns content float: left">
    <h3><?= h('Public statistics') ?></h3>
    <table border="1">
        <tbody>
            <tr>
                <td></td>
                <?php 
                $min = $this->request->session()->read('statistics_limits')['weekmin'];
                $max = $this->request->session()->read('statistics_limits')['weekmax'];
                for ($x = $min; $x <= $max; $x++) {
                    echo "<td>$x</td>";
                } 
                ?>
            </tr>
            
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?= h($project['project_name']) ?></td>
                    <?php foreach ($project['reports'] as $report): ?>
                        <td><?= h($report) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody> 
    </table>
</div>
