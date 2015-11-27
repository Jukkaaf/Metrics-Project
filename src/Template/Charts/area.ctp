<?php echo $this->Highcharts->includeExtraScripts(); ?>
<div class="chart">
        <h4>Area Chart</h4>
        <div id="areawrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
        <div class="clear"></div>
        <?php echo $this->Highcharts->render($myChart, $chartName); ?>
</div>