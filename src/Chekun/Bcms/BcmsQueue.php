<?php namespace Chekun\Bcms;

use Chekun\Bcms\Job\BcmsJob;

class BcmsQueue extends Queue implements QueueInterface {

    /**
     * The Baidu Cloud Message Service Client instance.
     *
     * @var \Chekun\Bcms\Bcms
     */
    protected $bcms;

    /**
     * The name of the default tube.
     *
     * @var string
     */
    protected $default;

    /**
     * Create a new Baidu Cloud Message Service Client instance.
     *
     * @param  \Chekun\Bcms\Bcms $sqs
     * @param  string  $default
     * @return void
     */
    public function __construct(Bcms $bcms, $default)
    {
        $this->bcms = $bcms;
        $this->default = $default;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return void
     */
    public function push($job, $data = '', $queue = null)
    {
        $payload = $this->createPayload($job, $data);

        return $this->bcms->publish($payload, $queue);
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  int     $delay
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return void
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        $payload = $this->createPayload($job, $data);

        return $this->bcms->publish($payload, $queue);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $queue
     * @return \Illuminate\Queue\Jobs\Job|null
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        $response = $this->bcms->fetch($queue);

        if (count($response->messages) > 0)
        {
            return new BcmsJob($this->container, $this->bcms, $queue, $response->messages[0]);
        }
    }

    /**
     * Get the queue or return the default.
     *
     * @param  string|null  $queue
     * @return string
     */
    protected function getQueue($queue)
    {
        return $queue ?: $this->default;
    }

    /**
     * Get the underlying bcms instance.
     *
     * @return \\Chekun\Bcms\Bcms
     */
    public function getBcms()
    {
        return $this->bcms;
    }

}
