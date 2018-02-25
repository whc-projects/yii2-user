<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/whc/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace whc\user;

use Yii;
use yii\authclient\Collection;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /** @var array Model's map */
    private $_modelMap = [
        'User'             => 'whc\user\models\User',
        'Account'          => 'whc\user\models\Account',
        'Profile'          => 'whc\user\models\Profile',
        'Token'            => 'whc\user\models\Token',
        'RegistrationForm' => 'whc\user\models\RegistrationForm',
        'ResendForm'       => 'whc\user\models\ResendForm',
        'LoginForm'        => 'whc\user\models\LoginForm',
        'SettingsForm'     => 'whc\user\models\SettingsForm',
        'RecoveryForm'     => 'whc\user\models\RecoveryForm',
        'UserSearch'       => 'whc\user\models\UserSearch',
    ];

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                $class = "whc\\user\\models\\" . $name;
                Yii::$container->set($class, $definition);
                $modelName = is_array($definition) ? $definition['class'] : $definition;
                $module->modelMap[$name] = $modelName;
                if (in_array($name, ['User', 'Profile', 'Token', 'Account'])) {
                    Yii::$container->set($name . 'Query', function () use ($modelName) {
                        return $modelName::find();
                    });
                }
            }

            Yii::$container->setSingleton(Finder::className(), [
                'userQuery'    => Yii::$container->get('UserQuery'),
                'profileQuery' => Yii::$container->get('ProfileQuery'),
                'tokenQuery'   => Yii::$container->get('TokenQuery'),
                'accountQuery' => Yii::$container->get('AccountQuery'),
            ]);

            if ($app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'whc\user\commands';
            } else {
                Yii::$container->set('yii\web\User', [
                    'enableAutoLogin' => true,
                    'loginUrl' => ['/user/security/login'],
                    'identityClass' => $module->modelMap['User'],
                ]);

                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'rules'  => $module->urlRules,
                ];

                if ($module->urlPrefix != 'user') {
                    $configUrlRule['routePrefix'] = 'user';
                }

                $configUrlRule['class'] = 'yii\web\GroupUrlRule';
                $rule = Yii::createObject($configUrlRule);

                $app->urlManager->addRules([$rule], false);

                if (!$app->has('authClientCollection')) {
                    $app->set('authClientCollection', [
                        'class' => Collection::className(),
                    ]);
                }
            }

            if (!isset($app->get('i18n')->translations['user*'])) {
                $app->get('i18n')->translations['user*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }

            Yii::$container->set('whc\user\Mailer', $module->mailer);

            $module->debug = $this->ensureCorrectDebugSetting();
        }
    }

    /** Ensure the module is not in DEBUG mode on production environments */
    public function ensureCorrectDebugSetting()
    {
        if (!defined('YII_DEBUG')) {
            return false;
        }
        if (!defined('YII_ENV')) {
            return false;
        }
        if (defined('YII_ENV') && YII_ENV !== 'dev') {
            return false;
        }
        if (defined('YII_DEBUG') && YII_DEBUG !== true) {
            return false;
        }

        return Yii::$app->getModule('user')->debug;
    }
}
