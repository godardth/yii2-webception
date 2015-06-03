<?php

namespace frontend\modules\accounting\widgets\editable;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

class Editable extends \yii\base\Widget{

	// Widgets Parameters
    public $container = 'h1';
    public $containerOptions = [
        'class' => '', 
    ];
    
    // Element to be modified
    public $identifier = 0;
    public $property = '';
    public $default = null;
    public $text = '';
    public $action = '#';
    
    // Initialize the widget
    public function init(){
        parent::init();
        
        // Assets Registration
		EditableAsset::register($this->getView());
		
		// Starts output buffering
        ob_start();
        ob_implicit_flush(false);
	}
	
	// Rendering
    public function run(){
		
		// Main Container
		echo Html::beginTag('div', [
		    'class' => 'fp-editable',
		    'ng-app' => 'editableApp',
		    'ng-controller' => 'EditableController'
	    ]);
		
		// Edition Form
		$form = ActiveForm::begin([
		    'id' => 'account-title-edit-form', 
		    'class' => 'form-inline',
		    'ng-blur' => 'save()',
	    ]); 
    	    echo Html::beginTag('div', ['class' => 'form-container']);
                echo Html::input('hidden', 'id', $this->identifier);
                echo Html::input('text', 'value', '', [
                    'id' => 'account-title-edit-form-field',
                    'class' => 'form-field-invisible text-center h1',
                    'ng-focus' => 'editionMode=true',
                    //'ng-blur' => 'save()',
                    'ng-model' => 'value'
                ]);
                echo Html::beginTag('div', ['class' => 'buttons-line pull-right form-inline']);
                    echo Html::button('Default', [
                        'id' => 'account-title-edit-form-reset-button',
                        'class' => 'btn btn-xs',
                        'ng-click' => 'reset()',
                        'ng-show' => 'editionMode',
                    ]); 
                    /*echo Html::button('Save', [
                        'id' => 'account-title-edit-form-button',
                        'class' => 'btn btn-xs btn-primary',
                        'ng-click' => 'save()',
                        'ng-show' => 'editionMode',
                    ]);*/
                echo Html::endTag('div');
            echo Html::endTag('div');
        $form->end();
        
        echo Html::endTag('div');
        
		// Register the initial values to be passed to the angular app
	    $this->getView()->registerJs('window.fpEditableId = "'.$this->identifier.'";', 1); 
	    $this->getView()->registerJs('window.fpEditableAction = "'.$this->action.'";', 1); 
	    $this->getView()->registerJs('window.fpEditableInitial = "'.$this->text.'";', 1); 
	    $this->getView()->registerJs('window.fpEditableDefault = "'.($this->default?$this->default:$this->text).'";', 1);
		
		return ob_get_clean();
    }
    
}

?>
