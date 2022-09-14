<?php

use mvc\Config\router;
use mvc\Controller\Controller1;
use mvc\Controller\Controller2;
use mvc\Controller\Controller3;
use mvc\Middleware\Middleware1;
use mvc\Config\Request;

router::get('', function () {
    return "Welcome to MVC";
});

router::get('indexFunction', function () {
    return Controller1::index();
})->middleware('test');

router::get('show', function () {
    return Controller1::show();
});

router::post('getData', function () {
    return Controller3::index();
});

router::get('get', function () {
    return Controller2::index();
});

// router::get('get3', function () {
//     return Controller3::index();
// });

router::get('showrequest', function () {
    return Controller3::showRequest();
});

router::get('api', function () {
    return Controller1::Api();
})->middleware('test');

router::get('responseTest', function () {
    return Controller2::responseTest();
});

router::get('middleware', function () {
    return Controller2::middleware();
});
?>