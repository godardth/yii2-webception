<?php

namespace frontend\modules\accounting\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

use frontend\modules\accounting\models\Transaction;
use frontend\modules\accounting\models\Account;
use frontend\modules\accounting\models\AccountPlus;
use frontend\modules\accounting\models\AccountHierarchy;

class BalancesheetController extends Controller
{    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function getFinancialData(){
        
        $assets = AccountPlus::findOne(['owner_id' => Yii::$app->user->id, 'name' => 'Assets']);
        $equity = AccountPlus::findOne(['owner_id' => Yii::$app->user->id, 'name' => 'Equity']);
        $liabilities = AccountPlus::findOne(['owner_id' => Yii::$app->user->id, 'name' => 'Liabilities']);
        
        $ret['total_assets'] = $assets->sign * $assets->value;
        $ret['total_equity'] = $equity->sign * $equity->value;
        $ret['total_liabilities'] = $liabilities->sign * $liabilities->value;
        
        $ret['debt_ratio'] = round(($ret['total_assets']!=0)?(100 * $ret['total_liabilities'] / $ret['total_assets']):0, 1);    
        
        return $ret;
    }
    
    public function actionIndex()
    {
        $assets = AccountHierarchy::findOne(['owner_id' => Yii::$app->user->id, 'name' => 'Assets']);
        $equity = AccountHierarchy::findOne(['owner_id' => Yii::$app->user->id, 'name' => 'Equity']);
        $liabilities = AccountHierarchy::findOne(['owner_id' => Yii::$app->user->id, 'name' => 'Liabilities']);
        
        $this->layout = '@app/views/layouts/two-columns-left';
        return $this->render('balancesheet', [
            'assets' => $assets, 
            'equity' => $equity, 
            'liabilities' => $liabilities,
            'back_button' => ['text' => 'Accounting', 'route' => '/accounting'],
            'left_menus' => [
                [
                    'title' => 'Reporting', 'items' => [
                        ['icon' => 'pie-chart', 'text' => 'Balance Sheet', 'type' => 'regular', 'route' => '/accounting/balancesheet'],
                        ['icon' => 'bar-chart', 'text' => 'Income', 'type' => 'regular', 'route' => '/accounting/profitloss'],
                        ['icon' => 'random', 'text' => 'Cash Flow', 'type' => 'regular', 'route' => '/accounting'],
                    ]
                ],
                [
                    'title' => 'Operations', 'items' => [
                        ['icon' => 'plus', 'text' => 'Transaction', 'type' => 'modal_preload', 'route' => 'transaction/create'], 
                        ['icon' => 'plus', 'text' => 'Account', 'type' => 'modal_preload', 'route' => 'account/create'],
                    ]
                ]
            ]
        ]);
    }
    
    public function actionOverview()
    {
        $data = $this->getFinancialData();
        
        return $this->renderAjax('overview', [
            'data' => $data
        ]);
    }
    
}