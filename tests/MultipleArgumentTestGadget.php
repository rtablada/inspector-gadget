<?php  namespace Rtablada\InspectorGadget\Test;

class MultipleArgumentTestGadget
{
    public function render($str, $str2)
    {
        return "$str $str2";
    }
}
