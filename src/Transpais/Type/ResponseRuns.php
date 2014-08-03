<?php
/**
 * Created by PhpStorm.
 * User: degaray
 * Date: 7/3/14
 * Time: 12:24 PM
 */

namespace Transpais\Type;

use Transpais\Type\Errors\TypeException;

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
