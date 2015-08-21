<?php
use yii\helpers\Html;
use yii\helpers\Url;

use godardth\yii2webception\assets\Yii2WebceptionAsset;
Yii2WebceptionAsset::register($this);

/**
 * Generating labels for different test types
 */
function genLabel($type){
    switch ($type) {
    case 'acceptance':
        $ret = '<span class="label label-primary">A</span>';
        break;
    case 'functional':
        $ret = '<span class="label label-info">F</span>';
        break;
    case 'unit':
        $ret = '<span class="label label-warning">U</span>';
        break;
    default:
        $ret = '<span class="label label-default">?</span>';
    }
    return $ret;
}


?>

<div class="container">
    <h1>
        Webception
        
        <div class="btn-group btn-group-xs" role="group" style="margin-left:50px">
            <button type="button" class="btn btn-<?= $checks['configuration'] ? 'success' : 'danger' ?> disabled">Configuration</button>
            <button type="button" class="btn btn-<?= $checks['executable']['passed'] ? 'success' : 'danger' ?> disabled">Executable</button>
            <button type="button" class="btn btn-<?= $checks['logging'] ? 'success' : 'danger' ?> disabled">Writeable</button>
        </div>
        
        <button class="btn btn-default btn-xs pull-right run-all" type="submit">Run All</button>
    </h1>
    
    <!-- Error -->
    <?php if (!$checks['executable']['passed']) : ?>
        <pre><samp>
            <?= $checks['executable']['error'] ?>
        </samp></pre>
    <?php endif; ?>
    
    <!-- Check the codeception initialization -->
    <!-- List the available sites and tests for each of them -->
    <table class="table table-condensed">
        <?php 
        $sitecounter = 0;
        foreach($sites as $site) : 
            $sitecounter++;
        ?>
            <thead>
                <tr>
                    <th colspan="2"><?= $site->name ?> 
                    <?php if(!$site->logging['passed']) : ?>
                        <button type="button" class="btn btn-danger disabled btn-xs">Logging</button>
                    <?php endif; ?>
                    </th>
                    <th>
                        <button class="btn btn-default btn-xs pull-right run-site" 
                        site="site<?= $sitecounter ?>"
                        type="submit">Run All</button>
                    </th>
                </tr>
            </thead>
            <tbody id="site<?= $sitecounter ?>">
                <?php foreach($site->tests as $test) : ?>
                    <tr id="<?= $test->hash ?>">
                        <td><?= genLabel($test->type) ?> <?= $test->title ?></td>
                        <td><span class="status label label-primary"><?= $test->state ?></span></td>
                        <td>
                            <button class="btn btn-default btn-xs pull-right run-test" 
                                    hash="<?= $test->hash ?>"
                                    type="submit">Run</button>
                        </td>
                    </tr>
                    <tr class="test-log <?= $test->hash ?>">
                        <td colspan="3">
                           <pre></pre>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php endforeach; ?>
    </table>
    
</div>