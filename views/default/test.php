<?php
use frontend\widgets\chartjs\ChartJs;
?>

<h1 class="text-center">Account Value Test</h1>

<div class="row">
    <div class="col-lg-12">
        <h1>Current Value</h1>
        <p><?= round($value,2) ?> <?= \Yii::$app->user->identity->acc_currency ?></p>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h1>Historical Values</h1>
        <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <?php foreach($currencies as $cur) : ?>
                <th><?= $cur ?></th>
                <?php endforeach; ?>
                <th>TOTAL</th>
            </tr>
        </thead>    
        <tbody>
            <?php foreach($histos as $date => $vals) : ?>
            <tr>
                <td><?= $date ?></td>
                <?php foreach($currencies as $cur) : ?>
                <td><?= (isset($vals[$cur])) ? $vals[$cur] : '-' ?></td>
                <?php endforeach; ?>
                <td><!--?= $vals['total'] ?--></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h1>Related Transactions</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID #</th>
                    <th>Date</th>
                    <th>Transaction</th> 
                    <th>Credit</th>
                    <th>Debit</th>
                    <th>Account Forex</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($trans as $t) : ?>
                <tr>
                    <td><?= $t->id ?></td>
                    <td><?= $t->date_value ?></td>
                    <td><?= $t->name ?></td>
                    <td><?= $t->valueCredit ?> <?= $t->accountCredit->currency ?></td>
                    <td><?= $t->valueDebit ?> <?= $t->accountDebit->currency ?></td>
                    <td><?= ($t->accountForex)? '#'.$t->accountForex->id : 'N/A' ?></td>
                </tr>
            <?php endforeach; ?> 
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h1>Daily Historical Values</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($daily as $d => $b) : ?>
                <tr>
                    <td><?= $d ?></td>
                    <td><?= round($b,2) ?></td>
                </tr>
            <?php endforeach; ?> 
            </tbody>
        </table>
    </div>
</div>

<?php
// Preparing the data
$keys = [];
$data1 = [];
foreach($daily as $d => $b) {
    array_push($keys, $d);
    array_push($data1, $b);
}
?>

<div class="row">
    <div class="col-lg-12">
        <?= ChartJs::widget([
            'type' => 'Line',
            'clientOptions' => [
                'showScale' => false,
                //'maintainAspectRatio' => false,
                'scaleShowGridLines' => false,
                //'scaleShowLabels' => false,
                'responsive' => true,
                'showTooltips' => false,
                'pointDot' => false,
            ],
            'data' => [
                'labels' => $keys,
                'datasets' => [
                    [
                        'fillColor' => "rgba(235,114,96,0.1)",
                        'strokeColor' => "rgb(58,154,217)",
                        'data' => $data1
                    ]
                ]
            ]
        ]);
        ?>
    </div>
</div>