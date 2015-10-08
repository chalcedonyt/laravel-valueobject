<?= '<?php' ?>

namespace {{$namespace}};

class {{$classname}} extends \Chalcedonyt\ValueObject\ValueObject
{
@if (count($parameters))
@foreach( $parameters as $param)
    /**
    * @var {{$param['class']}}
    */
    protected ${{$param['name']}};

@endforeach
@endif
    /**
    *
@if (!count($parameters))
    * Set properties here for a parameterized specification.
@else
@foreach( $parameters as $param)
    *  @param {{$param['class']}} ${{$param['name']}}
@endforeach
@endif
    */
    public function __construct( {{$parameter_string}})
    {
@if (count($parameters))
@foreach( $parameters as $param)
        $this -> {{$param['name']}} = ${{$param['name']}};
@endforeach
@endif
    }
}
