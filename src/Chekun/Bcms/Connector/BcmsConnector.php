<?php namespace Chekun\Bcms\Connector;

use Chekun\Bcms\Bcms;
use Chekun\Bcms\BcmsQueue;
use Illuminate\Queue\Connectors\ConnectorInterface;

class BcmsConnector implements ConnectorInterface {

    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Queue\QueueInterface
     */
    public function connect(array $config)
    {
        $bcms = new Bcms($config['client_id'], $config['client_secret'], $config['queue']);
        return new BcmsQueue($bcms, $config['queue']);
    }

}