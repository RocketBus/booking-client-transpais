<?php
namespace Transpais\Type;

use Transpais\Type\Errors;
use Transpais\Type\Errors\TypeException;

/**
 * Class ResponseRuns
 * @package Transpais\Type
 */
class ResponseRuns extends \ArrayObject
{
    public function append($corrida)
    {
        if (!$corrida instanceof Run) {
            throw new TypeException("A instance of Corrida is required");
        }

        parent::append($corrida);
    }
}
