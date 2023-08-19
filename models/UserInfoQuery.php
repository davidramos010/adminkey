<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserInfo]].
 *
 * @see UserInfo
 */
class UserInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
