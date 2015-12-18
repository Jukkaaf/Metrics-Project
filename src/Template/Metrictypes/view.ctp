<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Metrictype'), ['action' => 'edit', $metrictype->id]) ?> </li>
    </ul>
</nav>
<div class="metrictypes view large-5 medium-8 columns content float: left">
    <h3><?= h($metrictype->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= h($metrictype->description) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($metrictype->id) ?></td>
        </tr>
    </table>
</div>
