<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/06/15
 * Time: 09:54
 */

return [
    'providers' => [
        /*
         * Notos Service Providers...
         */
        Atrauzzi\LaravelDoctrine\ServiceProvider::class,
    ],
    'aliases' => [
        'EntityManager' => Atrauzzi\LaravelDoctrine\Support\Facades\Doctrine::class,
    ]
];