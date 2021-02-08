<?php


class myclass {
  static function say_hello($name='default name')
  {
      echo "Hello! $name";
  }
}

$classname = "myclass";

call_user_func(array($classname, 'say_hello'),'mohammad');
call_user_func($classname .'::say_hello'); // As of 5.2.3