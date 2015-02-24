<?php
namespace Transpais\Type;

use Transpais\Type\Errors\TypeException;

/**
 * Class StopsResponseFactory
 * @package Transpais\Type
 */
class StopsResponseFactory
{
    public static function create($AllOrigins)
    {
        if (!is_array($AllOrigins->out->Parada)) {
            $paradaObj[] = $AllOrigins->out->Parada;
        } else {
            $paradaObj = $AllOrigins->out->Parada;
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
