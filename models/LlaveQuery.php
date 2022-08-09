<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Llave]].
 *
 * @see Llave
 */
class LlaveQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Llave[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Llave|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
