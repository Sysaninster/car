<?php
/**
 * Created by PhpStorm.
 * User: Sysanin
 * Date: 21.12.2014
 * Time: 21:55
 */

namespace CarAdapter;


use RuntimeException;

class Car implements CarAdapterInterface
{

    /**
     * The port
     *
     * @var string
     */
    protected $port = '';
    
    /**
     * The fp
     *
     * @var resource
     */
    protected $fp;

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    public function connect()
    {
//        exec("mode {$this->getPort()} BAUD=9600 PARITY=N data=8 stop=1 xon=on");
        $this->fp = fopen($this->getPort(), 'c+b');
        if ($this->fp === false) {
            throw new RuntimeException('It can\'t open port ' . $this->getPort());
        }
    }

    public function close()
    {
        fclose($this->fp);
    }

    /**
     * @param int $speed
     * @return string
     */
    protected function normalizedSpeed($speed)
    {
        if ($speed >= 0) {
            return '+' . $speed;
        }
        return $speed;
    }

    /**
     * @param int $leftSpeed
     * @param int $rightSpeed
     */
    public function run($leftSpeed, $rightSpeed)
    {
        $this->executeCommand('GO', [$this->normalizedSpeed($leftSpeed), $this->normalizedSpeed($rightSpeed)]);
    }

    /**
     * @param string $command
     * @param array $args
     */
    protected function executeCommand($command, $args = [])
    {
        fwrite($this->fp, $command . ':' . implode('', $args) . "\n");
    }

}