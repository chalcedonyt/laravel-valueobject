# Value Object

A simple implementation of the Value Object pattern (http://c2.com/cgi/wiki?ValueObject) with some helpers for Laravel 5.

## Install

Via Composer

``` bash
$ composer require chalcedonyt/valueobject:1.*
```

Once composer is finished, add the service provider to the `providers` array in `app/config/app.php`:
```
Chalcedonyt\ValueObject\Providers\ValueObjectServiceProvider::class
```


## Usage

This package adds a helper generator for Value Objects to quickly create them.

``` php
php artisan make:valueobject NewValueObject

Enter the class or variable name for parameter 0 (Examples: \App\User or $user) [Blank to stop entering parameters] [(no_param)]:
 > $var1

 Enter the class or variable name for parameter 1 (Examples: \App\User or $user) [Blank to stop entering parameters] [(no_param)]:
 > $var2

```

```
<?php
namespace App\ValueObjects;

class NewValueObject extends Chalcedonyt\ValueObject\ValueObject
{
    /**
    * @var
    */
    protected $var1;

    /**
    * @var
    */
    protected $var2;

    /**
    *
    *  @param $var1
    *  @param $var2
    */
    public function __construct( $var1, $var2)
    {
        $this -> var1 = $var1;
        $this -> var2 = $var2;
    }
}
```
It also introduces a static method `create` that will return an instance of the ValueObject from an array.

```
$args = ['var1' => 1, 'var2' => 2];
$obj = NewValueObject::create($args);
$obj -> __toString(); //"{"var1":1,"var2":2}"

```

## Change log

Please see [CHANGELOG] for more information what has changed recently.
