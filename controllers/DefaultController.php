<?php

namespace frontend\modules\accounting\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

use frontend\components\ExchangeController;

use frontend\modules\accounting\models\Account;
use frontend\modules\accounting\models\AccountHierarchy;

class DefaultController extends \frontend\components\Controller
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
    
    public function actionIndex()
    {
        $data = BalancesheetController::getFinancialData();
    
        $this->layout = '@app/views/layouts/two-columns-left';
        return $this->render('index' , [
            'data' => $data,
            'back_button' => ['text' => 'Home', 'route' => '/'],
            'left_menus' => [
                [
                    'title' => 'Reporting', 'items' => [
                        ['icon' => 'pie-chart', 'text' => 'Balance Sheet', 'type' => 'regular', 'route' => '/accounting/balancesheet'],
                        ['icon' => 'bar-chart', 'text' => 'Income', 'type' => 'regular', 'route' => '/accounting/profitloss'],
                        ['icon' => 'random', 'text' => 'Cash Flow', 'type' => 'regular', 'route' => '/accounting'],
                    ]
                ],
                [
                    'title' => 'Accounting', 'items' => [
                        ['icon' => 'plus', 'text' => 'Transaction', 'type' => 'modal_preload', 'route' => '/accounting/transaction/create'],
                        ['icon' => 'plus', 'text' => 'Account', 'type' => 'modal_preload', 'route' => 'account/create'],
                    ]
                ]
            ]
        ]);
    }
	
	public function actionTest() { 
		
		$rate = ExchangeController::get('finance','currency-conversion', [
		    'value' => '1500',
		    'from' => 'KRW',    
		    'to' => 'EUR',    
	    ]);
		
		return $this->render('test', ['rate' => $rate]);
	}
}
