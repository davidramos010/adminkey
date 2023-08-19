<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LlaveStatus]].
 *
 * @see LlaveStatus
 */
class LlaveStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LlaveStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LlaveStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
