<?php

namespace godardth\yii2webception\models;

use Yii;

/**
 * This is the model class for Site.
 */
class Site extends \yii\db\ActiveRecord
{
    
    public $tests = [];
    public $configuration = null;
    public $logging = false;
    
    public static function getDb()
	{
		return \Yii::$app->controller->module->db;
   	}
   	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'config'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => 'Name',
            'config' => 'Configuration File',
        ];
    }
    
    public function loadConfig($full_path) {
        $path = pathinfo($full_path)['dirname'] . '/';
        $file = pathinfo($full_path)['basename'];

        // If the Codeception YAML can't be found, the application can't go any further.
        if (! file_exists($full_path))
            return false;

        // Using Symfony's Yaml parser, the file gets turned into an array.
        $config = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($full_path));

        // Update the config to include the full path.
        foreach ($config['paths'] as $key => &$test_path) {
            $test_path = file_exists($path . $test_path) ?
                 realpath($path . $test_path) : $path . $test_path;
        }

        return $config;
    }
    
    public function getTests() {
        
        $config = \Yii::$app->controller->module->params;
        foreach ($config['tests'] as $type => $active) {
            
            // Get out of the loop in case the type is deactivated in config
            if (! $active) break;
            
            // Configure the file iterator
            $directory = new \RecursiveDirectoryIterator(
                "{$this->configuration['paths']['tests']}/{$type}/", 
                \RecursiveDirectoryIterator::KEY_AS_FILENAME | \RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
            );
            $phpfiles = new \RegexIterator(
                new \RecursiveIteratorIterator($directory), 
                '/^.+(Cept|Cest|Test)\.php$/i',
                \RegexIterator::MATCH, 
                \RegexIterator::USE_KEY
            );
            
            foreach ($phpfiles as $file) {
                if (! in_array($file->getFilename(), $config['ignore']) && $file->isFile()) {
                    $test = new Test();
                    $test->initialize($type, $file);
                    array_push($this->tests, $test);
                    unset($test);
                }
            }
        }
    }
    
    public function afterFind() {
        parent::afterFind();
        $this->configuration = self::loadConfig($this->config);
        $this->getTests();
        $this->checkLogging();
    }
    
    public function checkLogging() {
        $response = array();
        $path = $this->configuration['paths']['log'];
        $response['resource'] = $path;

        if (is_null($path)) {
            $response['error'] = 'The Codeception Log is not set. Is the Codeception configuration set up?';
        } elseif (! file_exists($path)) {
            $response['error'] = 'The Codeception Log directory does not exist. Please check the following path exists:';
        } elseif (! is_writeable($path)) {
            $response['error'] = 'The Codeception Log directory can not be written to yet. Please check the following path has \'chmod 777\' set:';
        }

        $response['passed'] = ! isset($response['error']);

        $this->logging = $response;
    }
    
    // FROM ORIGINAL WEBCEPTION

    /**
     * Hash value of the current site.
     *
     * @var false if not set; string if set.
     */
    private $hash = false;
    
}
