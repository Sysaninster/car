<?php
/**
 * Created by PhpStorm.
 * User: Sysanin
 * Date: 21.12.2014
 * Time: 19:49
 */

namespace Console;


use Adapter\Car;
use Psf\Interfaces\ApplicationInterface;
use Psf\Shell;

class RunShell extends Shell implements ApplicationInterface
{

    CONST DIRECTION_DIRECT = 0;
    CONST DIRECTION_BACK = 1;
    CONST DIRECTION_LEFT = 2;
    CONST DIRECTION_RIGHT = 3;

    /**
     * The carAdapted
     *
     * @var Car
     */
    protected $carAdapted;

    /**
     * The speed
     *
     * @var int
     */
    protected $speed = 0;
    
    /**
     * The direction
     *
     * @var int
     */
    protected $direction = self::DIRECTION_DIRECT;

    public function configure()
    {
        $this->carAdapted = new Car();
    }

    public function main()
    {
        $this->out('Port?' . PHP_EOL);
        $this->carAdapted->setPort($this->read());
        $this->carAdapted->connect();
        $this->out('Write commands to "' . $this->carAdapted->getPort() . '"' . PHP_EOL);
        while(true) {
            $command = $this->read();
            if ($command === 'q') {
                break;
            } else if ('0' <= $command && $command <= '5') {
                $this->speed = (int)$command;
            } else if ($command === ' ') {
                $this->speed = 0;
            } else if ($command === 'w') {
                $this->direction = static::DIRECTION_DIRECT;
            } else if ($command === 's') {
                $this->direction = static::DIRECTION_BACK;
            } else if ($command === 'a') {
                $this->direction = static::DIRECTION_LEFT;
            } else if ($command === 'd') {
                $this->direction = static::DIRECTION_RIGHT;
            }
            $this->out('Read: ' . $command . PHP_EOL);
            $this->updateSpeed();
        }
        $this->carAdapted->close();
    }

    protected function updateSpeed()
    {
        $leftSpeed = 0;
        $rightSpeed = 0;
        if ($this->direction === static::DIRECTION_DIRECT || $this->direction === static::DIRECTION_BACK) {
            $leftSpeed = $this->speed;
            $rightSpeed = $this->speed;

            if ($this->direction === static::DIRECTION_BACK) {
                $leftSpeed *= -1;
                $rightSpeed *= -1;
            }
        } else if ($this->direction === static::DIRECTION_LEFT) {
            $leftSpeed = 0;
            $rightSpeed = $this->speed;
        } else if ($this->direction === static::DIRECTION_RIGHT) {
            $leftSpeed = $this->speed;
            $rightSpeed = 0;
        }
        $this->carAdapted->run($leftSpeed, $rightSpeed);
    }
}