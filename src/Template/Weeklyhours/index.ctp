<nav class="large-2 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <?php
            $admin = $this->request->session()->read('is_admin');
            if($admin){
        ?>
            <li><?= $this->Html->link(__('New Weeklyhour'), ['action' => 'add']) ?></li>
        <?php
            }
        ?> 
    </ul>
</nav>
<div class="weeklyhours index large-7 medium-8 columns content  float: left">
    <h3><?= __('Weeklyhours') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('member_id') ?></th>
                <th><?= $this->Paginator->sort('weeklyreport_id') ?></th>
                <th><?= $this->Paginator->sort('duration') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($weeklyhours as $weeklyhour): ?>
            <tr>        
                <?php
                    foreach($memberlist as $member){
                        if($weeklyhour->member->id == $member['id']){
                           $weeklyhour->member['member_name'] = $member['member_name'];
                        }
                    }
                ?>
                <td><?= $weeklyhour->has('member') ? $this->Html->link($weeklyhour->member->member_name, ['controller' => 'Members', 'action' => 'view', $weeklyhour->member->id]) : '' ?></td>
                <td><?= $weeklyhour->has('weeklyreport') ? $this->Html->link($weeklyhour->weeklyreport->title, ['controller' => 'Weeklyreports', 'action' => 'view', $weeklyhour->weeklyreport->id]) : '' ?></td>
                <td><?= $this->Number->format($weeklyhour->duration) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $weeklyhour->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $weeklyhour->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
