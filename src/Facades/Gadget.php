<?php  namespace Rtablada\InspectorGadget\Facades;

use Illuminate\Support\Facades\Facade;

class Gadget extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'gadget';
    }
}
