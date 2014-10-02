<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/22/14
 * Time: 12:23 PM
 */

namespace Transpais\Type;


use Transpais\Type\Errors\TypeException;

class StopsResponseFactory
{
    public static function create($AllOrigins)
    {
        $paradaObj = $AllOrigins->out->Parada;
        if (!is_array($AllOrigins->out->Parada)) {
            $paradaObj[] = $AllOrigins->out->Parada;
        }

        foreach ($paradaObj as $origin) {

            if (!is_int($origin->id)) {
                throw new TypeException('Parada Id must be a numeric value');
            }

            if (!is_string($origin->descripcion)) {
                throw new TypeException('La Descipción must be a string');
            }

            $origins[] = (object) array(
                'id' => $origin->id,
                'description' => $origin->descripcion,
            );
        }

        return $origins;
    }
}
