<?php
namespace Transpais\Type;

/**
 * Class ResponseSeatMapFactory
 * @package Transpais\Type
 */
class ResponseSeatMapFactory
{
    public static function create($soap_response)
    {
        $responseSeatMap = new ResponseSeatMap();

        $detalleDiagrama = $soap_response->out->detallesDiagrama->DetalleDiagrama;
        if (!is_array($detalleDiagrama)) {
            $asientos[] = $detalleDiagrama;
        } else {
            $asientos = $detalleDiagrama;
        }

        foreach ($asientos as $asiento) {
            $seat = SeatFactory::create($asiento);
            $responseSeatMap->appendSeat($seat);
        }

        $disponibilidad = $soap_response->out->disponibilidad->Disponibilidad;
        if (!is_array($disponibilidad)) {
            $disponibles[] = $disponibilidad;
        } else {
            $disponibles = $disponibilidad;
        }

        foreach ($disponibles as $disponible) {
            $seat_type = (object) array(
                'quantity' => $disponible->cantidad,
                'categoryId' => $disponible->categoriaId,
                'categoryDesc' => $disponible->descCategoria,
                'price' => $disponible->precio
            );
            $responseSeatMap->appendSeatType($seat_type);
        }

        return $responseSeatMap;
    }
}
