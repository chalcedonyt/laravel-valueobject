<?php
namespace Chalcedonyt\ValueObject;
/**
 * Helper functions to create a value object
* Value objects should have immutable properties
 */
abstract class ValueObject
{

    /**
     * @return String json encoded
     */
    public function __toString()
    {
        return json_encode(get_object_vars($this), true);
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
    /**
     * Creates an instance of the ValueObject from an array.
     * Array $values
     */
    public static function create( Array $values )
    {
        $r = new \ReflectionClass( get_called_class() );
        $new = $r -> newInstanceWithoutConstructor();

        foreach( $values as $key => $value ){
            if( $r -> hasProperty( $key )){
                $property = $r -> getProperty( $key );
                $property -> setAccessible(true);
                $property -> setValue( $new, $value );
            }
        }
        return $new;
    }
}

?>
