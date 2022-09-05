<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Propietarios]].
 *
 * @see Propietarios
 */
class PropietariosQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Propietarios[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Propietarios|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
