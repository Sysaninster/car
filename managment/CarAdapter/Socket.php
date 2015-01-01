<?php
/**
 * Created by PhpStorm.
 * User: Sysanin
 * Date: 02.01.2015
 * Time: 0:34
 */

namespace CarAdapter;


use RuntimeException;

class Socket implements CarAdapterInterface
{
    
    /**
     * The socket
     *
     * @var resource
     */
    protected $socket;

    public function connect()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!socket_connect($this->socket, '127.0.0.1', 5445)) {
            throw new RuntimeException('It can\'t connect to server');
        }
    }

    public function close()
    {
        socket_close($this->socket);
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
        socket_write($this->socket, $command . ':' . implode('', $args) . "\n");
    }
}