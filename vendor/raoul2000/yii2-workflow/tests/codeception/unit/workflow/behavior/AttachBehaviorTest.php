<?php

namespace tests\unit\workflow\behavior;

use Yii;
use yii\codeception\TestCase;
use tests\codeception\unit\models\Item01;
use yii\base\InvalidConfigException;
use raoul2000\workflow\base\SimpleWorkflowBehavior;

class AttachBehaviorTest extends TestCase
{
	use \Codeception\Specify;
	use \Codeception\AssertThrows;


    public function testAttachSuccess1()
    {
    	$model = new Item01();

    	$this->specify('behavior can be attached to ActiveRecord', function () use ($model) {
    		$behaviors = $model->behaviors();
    		expect('model should have the "workflow" behavior attached', isset($behaviors['workflow']) )->true();
    		expect('model has a SimpleWorkflowBehavior attached', SimpleWorkflowBehavior::isAttachedTo($model) )->true();
    	});
    }

    public function testAttachSuccess2()
    {
    	$this->specify('behavior can be attached to a Component with the "status" property', function () {
    		$model = Yii::createObject('\tests\codeception\unit\models\Component01',['status'=>'']);
    		$model->attachBehavior('workflow', SimpleWorkflowBehavior::className());
    	});
    }


    public function testAttachFails1()
    {
    	$this->specify('behavior cannot be attached if the owner has no suitable attribute or property to store the status', function () {
				$this->assertThrowsWithMessage(
					'yii\base\InvalidConfigException' ,
					"Property not found for owner model : 'status'",
					function() {
						$model = Yii::createObject("yii\base\Component",[]);
		    		$model->attachBehavior('workflow', SimpleWorkflowBehavior::className());
					}
				);
    	});
    }

    public function testAttachFails2()
    {
    	$this->specify('the status attribute cannot be empty', function () {
				$this->assertThrowsWithMessage(
					'yii\base\InvalidConfigException' ,
					'The "statusAttribute" configuration for the Behavior is required.',
					function() {
						$model = new Item01();
		    		expect('model has a SimpleWorkflowBehavior attached', SimpleWorkflowBehavior::isAttachedTo($model) )->true();
		    		$model->detachBehavior('workflow');
		    		expect('model has a NO SimpleWorkflowBehavior attached', SimpleWorkflowBehavior::isAttachedTo($model) )->false();
		    		$model->attachBehavior('workflow', [ 'class' =>  SimpleWorkflowBehavior::className(), 'statusAttribute' => '' ]);
					}
				);
    	});
    }

    public function testAttachFails3()
    {
    	$this->specify('the status attribute must exist in the owner model', function () {

				$this->assertThrowsWithMessage(
					'yii\base\InvalidConfigException' ,
					"Attribute or property not found for owner model : 'not_found'",
					function() {
						$model = new Item01();
		    		$model->detachBehavior('workflow');
		    		$model->attachBehavior('workflow', [ 'class' =>  SimpleWorkflowBehavior::className(), 'statusAttribute' => 'not_found' ]);
					}
				);
    	});
    }
}
