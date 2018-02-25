<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace whc\user\traits;

use whc\user\events\AuthEvent;
use whc\user\events\ConnectEvent;
use whc\user\events\FormEvent;
use whc\user\events\ProfileEvent;
use whc\user\events\ResetPasswordEvent;
use whc\user\events\UserEvent;
use whc\user\models\Account;
use whc\user\models\Profile;
use whc\user\models\RecoveryForm;
use whc\user\models\Token;
use whc\user\models\User;
use yii\authclient\ClientInterface;
use yii\base\Model;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
trait EventTrait
{
    /**
     * @param  Model     $form
     * @return FormEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFormEvent(Model $form)
    {
        return \Yii::createObject(['class' => FormEvent::className(), 'form' => $form]);
    }

    /**
     * @param  User      $user
     * @return UserEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getUserEvent(User $user)
    {
        return \Yii::createObject(['class' => UserEvent::className(), 'user' => $user]);
    }

    /**
     * @param  Profile      $profile
     * @return ProfileEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getProfileEvent(Profile $profile)
    {
        return \Yii::createObject(['class' => ProfileEvent::className(), 'profile' => $profile]);
    }


    /**
     * @param  Account      $account
     * @param  User         $user
     * @return ConnectEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getConnectEvent(Account $account, User $user)
    {
        return \Yii::createObject(['class' => ConnectEvent::className(), 'account' => $account, 'user' => $user]);
    }

    /**
     * @param  Account         $account
     * @param  ClientInterface $client
     * @return AuthEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getAuthEvent(Account $account, ClientInterface $client)
    {
        return \Yii::createObject(['class' => AuthEvent::className(), 'account' => $account, 'client' => $client]);
    }

    /**
     * @param  Token        $token
     * @param  RecoveryForm $form
     * @return ResetPasswordEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getResetPasswordEvent(Token $token = null, RecoveryForm $form = null)
    {
        return \Yii::createObject(['class' => ResetPasswordEvent::className(), 'token' => $token, 'form' => $form]);
    }
}
