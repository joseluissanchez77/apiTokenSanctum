<?php

namespace App\Traits;

trait TranslateException
{

    //php artisan config:cache & php artisan config:clear
    //cuando no realice la traducion
    /**
     * Cambiar de idioma el nombre 
     * del modelo cuando se use una Exception
     */
    function translateNameModel($model)
    {
        //Obtener el idioma del archivo .env
        if ((getenv('APP_LANGUAGE')) == 'es') {

            //obtener el arreglo del archivo validation.php
            $arrayModelNameTraslate = __('validation.modelNameTraslate');

            /**
             * verificar si existe la traducion del modelo 
             * si no existe retorna el nombre del modelo 
             */
            if (!array_key_exists($model, $arrayModelNameTraslate)) {
                return $model;
            }

            /**
             * Si existe se recorre y se busca la traducion al 
             * español
             */
            foreach ($arrayModelNameTraslate as $key => $value) {
                if ($key == $model) {
                    //Retorna el nombre traducido
                    return $value;
                }
            }
        }
        //retorna el nombre tal cual se llame en el model
        return $model;
    }
}
