<?php
return [
    'components' => [
        // list of component configurations
    ],
    'params' => [
        /*
        |--------------------------------------------------------------------------
        | Codeception Configurations
        |--------------------------------------------------------------------------
        |
        | This is where you add your Codeception configurations.
        |
        | Webception allows you to have access test suites for multiple applications.
        |
        | Place them in the order you want and they'll appear in the drop-down list
        | in the front-end. The first site in the list will become the default
        | site that's loaded on session load.
        |
        | Just add the site name and full path to the 'codeception.yml' below and you're set.
        |
        */
    
        'sites' => [
    
            'FullPlanner'       => '/var/www/fullplanner2/advanced/frontend/tests/codeception.yml', 
            'Accounting'        => '/var/www/fullplanner2/advanced/frontend/modules/accounting/tests/codeception.yml',
    
        ],
    
        /*
        |--------------------------------------------------------------------------
        | Codeception Executable
        |--------------------------------------------------------------------------
        |
        | Codeception is installed as a dependancy of Webception via Composer.
        |
        | You might need to set 'sudo chmod a+x vendor/bin/codecept' to allow Apache
        | to execute the Codeception executable.
        |
        */
    
        'executable' => dirname(__FILE__) .'/../../vendor/bin/codecept',
    
        /*
        |--------------------------------------------------------------------------
        | You get to decide which type of tests get included.
        |--------------------------------------------------------------------------
        */
    
        'tests' => [
            'acceptance' => TRUE,
            'functional' => TRUE,
            'unit'       => TRUE,
        ],
    
        /*
        |--------------------------------------------------------------------------
        | When we scan for the tests, we need to ignore the following files.
        |--------------------------------------------------------------------------
        */
    
        'ignore' => [
            'WebGuy.php',
            'TestGuy.php',
            'CodeGuy.php',
            '_bootstrap.php',
            '.DS_Store',
            // Ignoring the default tests created by codeception
            'AcceptanceTester.php',
            'FunctionalTester.php',
            'UnitTester.php',
        ],
    
        /*
        |--------------------------------------------------------------------------
        | Setting the location as the current file helps with offering information
        | about where this configuration file sits on the server.
        |--------------------------------------------------------------------------
        */
    
        'location' => __FILE__,
    ],
];