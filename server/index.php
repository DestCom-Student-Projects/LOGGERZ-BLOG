<?php

header("Access-Control-Allow-Origin: *");

spl_autoload_register(function($className){
    require $className . '.php';
});

?>