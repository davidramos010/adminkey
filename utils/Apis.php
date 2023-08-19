<?php


namespace app\utils;


use Exception;
use Yii;

/**
 * Class Api
 * @author Josep Vidal during Coronavirus Outbreak 🔥 06-05-2019
 * @package app\utils
 *
 * Esta clase se deberia usar CADA VEZ que hagamos peticiones a una API de las de aqui declaradas
 * En caso de añadir nuevas aqui, seguir el modelo establecido
 *
 * @example
 * $lira = Apis::getApi(Apis::API_LIRA);
 * $url = $lira->getUrl('/contratos/insertar');
 * En url tendremos toda la url con su base incluida y bien concatenada
 *
 */
class Apis
{
    /**
     * APIS
     */
    const API_LIRA = 'lira';
    const API_LIRA2 = 'lira2';
    const API_INTRANETFE = 'intranetfe';
    const APIS_VALIDAS = [self::API_LIRA, self::API_LIRA2, self::API_INTRANETFE];

    /**
     * La api sobre la cual construïra la url, tiene que ser una de las utilizadas arriba
     * @var string
     */
    private string $_api;

    /**
     * Entorno por defecto que se utilizar a la hora de consultar a las apis
     * @var string
     */
    private string $_entorno;

    /**
     * Contiene todas las bases de la url para peticiones
     * @var array
     */
    private array $baseUrls;

    /**
     * Contiene todas las bases de la url para hacer peticiones de testeo de connexión
     * @var array|null
     */
    private ?array $testUrls;

    /**
     * Api constructor.
     * @param string $api
     * @param array $baseUrls
     * @param array $testUrls
     * @throws Exception
     */
    private function __construct(string $api, array $baseUrls, ?array $testUrls = [])
    {
        if (!in_array($api, self::APIS_VALIDAS)) {
            throw new Exception('La api instanciada no esta declarada ni validada.');
        }
        if (!isset(Yii::$app->params['environment'])) {
            throw new Exception('No esta definido el parametro "environment" en el fichero de params.');
        }
        if (!in_array(Entornos::getEntorno(), Entornos::ENTORNOS_VALIDOS)) {
            throw new Exception('El entorno definido no es ningúno de los validos.');
        }

        foreach ($baseUrls as $entorno => $baseUrl) {
            if (!in_array($entorno, Entornos::ENTORNOS_VALIDOS)) {
                throw new Exception('Uno de los entornos introducidos en la api no es valido.');
            }

            if (!in_array(substr($baseUrl, -1), ['/', '\\'])) {
                throw new Exception('Las urls base de la api deben terminar con / o con \\');
            }
        }

        $this->_api = $api;
        $this->_entorno = Entornos::getEntorno();
        $this->baseUrls = $baseUrls;
        $this->testUrls = $testUrls;
    }

    /**
     * Devuelve la url montada para poder la petición
     * Si se define una / al principio de la $url se eliminara
     * Se puede especificar un entorno para hacer overwrite al por defecto
     * @param string $url
     * @param string|null $entorno
     * @return string
     * @throws Exception
     */
    public function getUrl(string $url, ?string $entorno = null)
    {
        $entorno = $entorno ?? $this->_entorno;

        if (!in_array($entorno, Entornos::ENTORNOS_VALIDOS)) {
            throw new Exception('El entorno definido no es ningúno de los válidos.');
        }

        $url = Strings::eliminarContrabarra($url);

        return $this->baseUrls[$entorno] . $url;
    }

    /**
     * @param string $api
     * @return Apis
     * @throws Exception
     */
    public static function getApi(string $api): Apis
    {
        return self::getApis()[$api];
    }

    /**
     * @return Apis[]
     * @throws Exception
     */
    public static function getApis(): array
    {
        return [
            self::API_LIRA => new self(self::API_LIRA, [
                Entornos::ENTORNO_DEV => 'http://192.168.73.34:8080/',
                Entornos::ENTORNO_PRE => 'http://192.168.73.34:8080/',
                Entornos::ENTORNO_PRO => 'http://lira:8080/'
            ]),
            self::API_LIRA2 => new self(self::API_LIRA2, [
                Entornos::ENTORNO_DEV => 'http://192.168.0.19/web/app_dev.php/',
                Entornos::ENTORNO_PRE => 'http://192.168.0.19/web/app_dev.php/',
                Entornos::ENTORNO_PRO => 'http://fesrvlira02l.factorenergia.local/'
            ]),
            self::API_INTRANETFE => new self(self::API_INTRANETFE, [
                Entornos::ENTORNO_DEV => 'http://192.168.0.16/',
                Entornos::ENTORNO_PRE => 'http://192.168.0.16/',
                Entornos::ENTORNO_PRO => 'https://intranetfe/'
            ]),
        ];
    }

}