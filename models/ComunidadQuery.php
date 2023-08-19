<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[comunidad]].
 *
 * @see Comunidad
 */
class ComunidadQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Comunidad[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Comunidad|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
