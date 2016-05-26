<?php

/**
 * Created by IntelliJ IDEA.
 * User: sheershoff
 * Date: 9/4/15
 * Time: 5:51 PM
 */

namespace sheershoff\ModuleUrlRules;

use yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;


class ModuleUrlRules extends Component implements BootstrapInterface
{

    /** @var string[] Allowed modules IDs */
    private $_allowed_modules;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, [$this, 'processModuleUrlRules']);
    }

    /**
     * Setter for allowed modules. Checks if it is array.
     * @param string[] $value Array of allowed modules IDs
     * @throws ErrorException
     */
    public function setAllowedModules($value)
    {
        if(!is_array($value))
        {
            throw new ErrorException("Yii2 module url rules allowed modules should be array");
        }
        $this->_allowed_modules = $value;
    }

    /**
     * Getter for allowed modules
     * @return string[] Array of allowed modules IDs
     */
    public function getAllowedModules()
    {
        return $this->_allowed_modules;
    }

    /**
     * Adds URL rules from a module if it is requested, allowed and has URL rules.
     * Bind on APPLICATION::EVENT_BEFORE_REQUEST.
     * @param $event
     * @return bool
     */
    public function processModuleUrlRules($event)
    {
        if(is_a(Yii::$app,'yii\web\Application')) {
            if (!(Yii::$app->has('moduleUrlRules'))) return false; // check if this app has this component set up
            $route = Yii::$app->request->getPathInfo();
            $module = substr($route, 0, strpos($route, '/'));

            if (in_array($module, Yii::$app->moduleUrlRules->getAllowedModules()) && Yii::$app->hasModule($module)) {
                $module = Yii::$app->getModule($module);
                if (isset($module->urlRules)) {
                    $urlManager = Yii::$app->getUrlManager();
                    $urlManager->addRules($module->urlRules);
                }
            }
        }
        return true;
    }

}