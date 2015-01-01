<?php
/**
 * Created by PhpStorm.
 * User: Sysanin
 * Date: 02.01.2015
 * Time: 0:35
 */

namespace CarAdapter;


interface CarAdapterInterface {

    public function connect();

    public function close();

    /**
     * @param int $leftSpeed
     * @param int $rightSpeed
     */
    public function run($leftSpeed, $rightSpeed);

} 