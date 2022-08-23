<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Perfiles]].
 *
 * @see Perfiles
 */
class PerfilesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Perfiles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Perfiles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
