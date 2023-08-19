<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TipoLlave]].
 *
 * @see TipoLlave
 */
class TipoLlaveQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TipoLlave[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TipoLlave|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
