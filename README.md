Yii2 Module Url Rules
=====================

This extension allows declaring url rules for a module inside a module, making it easy to manage module url rules, e.g.
for API modules.

This module is distributed under the MIT License (MIT).

Requirements
------------

Yii >=2.0.0

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist sheershoff/yii2-module-urlrules
```

or add

```json
"sheershoff/yii2-module-urlrules": "~1.0.0"
```

to the require section of your composer.json.


Configuration
-------------

To use this extension, you have to configure the components section in your application configuration and add getUrlRules or urlRules to your modules.

In your `main.php` for the desired app add the `moduleUrlRules` component:

```php
'components' => [
        // ...
        'moduleUrlRules' => [
            'class' => '\sheershoff\ModuleUrlRules\ModuleUrlRules',
            // allowed modules lists the modules that affect the url rules
            'allowedModules' => ['v1'],
        ],
        // ...
```

Check that modules are declared and see the `urlManager` settings for your API app or at least enable the
`enablePrettyUrl` option. E.g.:

```php
return [
    'modules' => [
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        // ...
        // this config is suitable for an API app
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ],
];
```

In your `Module.php` for the module add the `getUrlRules` like the following:

```php
<?php
namespace api\modules\v1;
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\v1\controllers';

    public function getUrlRules()
    {
        return [
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => [self::getUniqueId().'/city'],
            ],
        ];
    }
}
```

Enjoy :-)

TODO
----

1. Make tests with different php and yii versions.
2. See if bootstrapping process can be optimized in a way that it doesn't bind in apps that do not have `moduleUrlRules`
component.
3. Check if the aliases map works to move the module to another slug or implement one in the extension.