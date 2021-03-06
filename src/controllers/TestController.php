<?php

namespace godardth\yii2webception\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/*
* TestController
* Groups all methods related to TestManagement
*/
class TestController extends Controller
{
    
    /**
     * @inheritdoc
     */
    /*public function behaviors()  {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [''],
                    ],
                ],
            ],
        ];
    }*/
    
    /*
    * Test Runner
    * Given a test type (acceptance, functional etc) and a hash,
    * load all the tests, find the test and then run it.
    *
    * The route is called via AJAX and the return repsonse is JSON.
    */
    public function actionRunTest($hash) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $site = null;
        $test = null;
        $sites = SiteController::getAvailableSites();
        foreach ($sites as $s) {
            foreach ($s->tests as $t) {
                if ($t->hash === $hash) {
                    $site = $s;
                    $test = $t;
                }
            }
        }
        
        $response = [
            'message'     => null,
            'run'         => false,
            'passed'      => false,
            'state'       => 'error',
            'log'         => null
        ];
        
        if (is_null($response['message'])) {
            $this->run($site, $test);
            
            $response['run']    = $test->ran();
            $response['log']    = $test->getLog();
            $response['passed'] = $test->passed();
            $response['state']  = $test->state;
            $response['title']  = $test->title;
        }
        
        return $response;

    }
	    
	/**
     * Given a test, run the Codeception test.
     *
     * @param  Test $test Current test to Run.
     * @return Test $test Updated test with log and result.
     */
    public function run($site, $test)
    {
        // Get the full command path to run the test.
        $command = TerminalController::getCommandPath($site, $test->type, $test->filename);

        // Attempt to set the correct writes to Codeceptions Log path.
        //@chmod($this->getLogPath(), 0777);

        // Run the helper function (as it's not specific to Codeception)
        // which returns the result of running the terminal command into an array.
        $output  = TerminalController::run_terminal_command($command);

        // Add the log to the test which also checks to see if there was a pass/fail.
        $test->setLog($output);

        return $test;
    }
}
