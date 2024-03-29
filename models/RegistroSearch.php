<?php

namespace app\models;

use app\models\Registro;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RegistroSearch represents the model behind the search form of `app\models\Registro`.
 */
class RegistroSearch extends Registro
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'id_llave', 'pendientes' ], 'integer'],
            [['username','entrada', 'salida', 'fecha_registro','observacion', 'codigo', 'username','comunidad','comercial','nombre_propietario','clientes','llaves','llaves_e','llaves_s','llaves_st','llaves_sp','nombre_responsable','propietario_responsable'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = Registro::find()->alias('r');
        $query->select([
            'r.*',
            '(CASE WHEN r.salida IS NOT NULL THEN r.salida ELSE r.entrada END) as fecha_registro ',
            'll.codigo',
            'u.username',
            'cm.nombre as comercial',
            "( SELECT group_concat(llv.codigo SEPARATOR ' , ') FROM llave llv WHERE llv.id IN (  
                   SELECT st.id_llave FROM llave_status st WHERE st.id_registro = r.id
              )) AS llaves",
            "( SELECT group_concat(llv.codigo SEPARATOR ' , ') FROM llave llv WHERE llv.id IN (  
                   SELECT st.id_llave FROM llave_status st WHERE st.id_registro = r.id and st.status = 'E'
              )) AS llaves_e",
            "( SELECT group_concat(llv.codigo SEPARATOR ' , ') FROM llave llv WHERE llv.id IN (  
                   SELECT st.id_llave FROM llave_status st WHERE st.id_registro = r.id and st.status = 'S'
              )) AS llaves_s",
            "( SELECT group_concat(c.nombre SEPARATOR ' , ') FROM comunidad c WHERE id IN (  
               SELECT DISTINCT l.id_comunidad FROM llave l 
               INNER JOIN llave_status sta ON ( sta.id_llave=l.id ) 
               WHERE sta.id_registro = r.id
            ) ) AS clientes",
            "( SELECT group_concat(CASE
                                        WHEN p.nombre_propietario IS NOT NULL THEN p.nombre_propietario
                                        WHEN p.nombre_representante IS NOT NULL THEN p.nombre_representante
                                        ELSE NULL
                                    END  SEPARATOR ' , ') FROM propietarios p WHERE p.id IN (
                SELECT DISTINCT l.id_propietario FROM llave l 
                       INNER JOIN llave_status stb ON ( stb.id_llave=l.id ) 
                       WHERE stb.id_registro = r.id
                    ) ) AS nombre_propietario",
            "(CASE WHEN pt.nombre_propietario IS NOT NULL THEN pt.nombre_propietario ELSE pt.nombre_representante END) as propietario_responsable",
            "( SELECT count(1) FROM llave_status st WHERE st.id_registro = r.id and st.status = 'S') AS llaves_st",
            "( SELECT count(1) 
             FROM llave_status lsp 
             WHERE lsp.id_registro >= r.id AND 
             lsp.status='E' AND 
             lsp.id_llave IN ( SELECT st.id_llave FROM llave_status st WHERE st.id_registro = r.id AND st.status = 'S' )
              ) AS llaves_sp "
        ]);
        $query->leftJoin('llave ll','r.id_llave = ll.id');
        $query->leftJoin('User u','r.id_user = u.id');
        $query->leftJoin('comerciales cm','r.id_comercial = cm.id');
        $query->leftJoin('propietarios pt','r.id_propietario = pt.id');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'r.id' => $this->id,
            'r.id_user' => $this->id_user,
            'id_llave' => $this->id_llave,
            'u.username' => $this->username,

        ]);

        if($this->nombre_responsable){
            $query->andFilterWhere(['LIKE', 'r.nombre_responsable', $this->nombre_responsable]);
        }

        if($this->comercial){

            $query->andFilterWhere(['or',
                ['LIKE', 'cm.nombre', $this->comercial],
                ['LIKE', 'pt.nombre_propietario', $this->comercial],
                ['LIKE', 'pt.nombre_representante', $this->comercial]]);


        }

        if($this->salida){
            $query->andFilterWhere([
                'LIKE', 'salida', Date('Y-m-d', strtotime($this->salida))
            ]);
        }

        if($this->entrada){
            $query->andFilterWhere([
                'LIKE', 'entrada', Date('Y-m-d', strtotime($this->entrada))
            ]);
        }

        if($this->fecha_registro){
            $query->andFilterWhere([
                'LIKE', 'entrada', Date('Y-m-d', strtotime($this->fecha_registro))
            ]);
            $query->andFilterWhere([
                'LIKE', 'salida', Date('Y-m-d', strtotime($this->fecha_registro))
            ]);
        }

        // ======================================================
        $query->andFilterWhere(['like', 'observacion', $this->observacion]);
        $query->andFilterWhere(['like', 'll.codigo', $this->codigo]);

        // ====================================================== andHaving
        if($this->llaves){
            $query->andHaving("llaves like :L",[':L' => "%".$this->llaves."%"]);
        }

        if($this->clientes){
            $query->andHaving("clientes like :C",[':C' => "%".$this->clientes."%"]);
        }

        if($this->propietarios){
            $query->andHaving("nombre_propietario like :P",[':P' => "%".$this->propietarios."%"]);
        }

        if(isset($params['pendientes']) && !empty($params['pendientes']) ){
            $this->pendientes = $params['pendientes'];
            $query->andHaving("llaves_st > llaves_sp");
        }

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_status($idRegistro, $strStatus)
    {
        $query = LlaveStatus::find()->alias('r')
            ->select([
                "r.*",
                "( SELECT count(1) 
                     FROM llave_status lsp 
                     WHERE lsp.id_registro >= r.id_registro AND 
                     lsp.id_llave = r.id_llave AND
                     lsp.status='E') AS llaves_e "
            ])->where(['id_registro'=>$idRegistro,'status'=>$strStatus]);
        $query->orderBy('id DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
