<?php namespace Chekun\Bcms\Job;

use Chekun\Bcms\Bcms;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Container\Container;

class BcmsJob extends Job {

    /**
     * The Baidu Cloud Message Service Client instance
     *
     * @var \Chekun\Bcms\Bcms
     */
    protected $bcms;

    /**
     * The queue URL that the job belongs to.
     *
     * @var string
     */
    protected $queue;

    /**
     * The Baidu Cloud Message Service job instance.
     *
     * @var array
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Chekun\Bcms\Bcms  $bcms
     * @param  string  $queue
     * @param  array   $job
     * @return void
     */
    public function __construct(Container $container,
                                Bcms $bcms,
                                $queue,
                                array $job)
    {
        $this->bcms = $bcms;
        $this->job = $job;
        $this->queue = $queue;
        $this->container = $container;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->job['message'], true));
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {

    }

    /**
     * Release the job back into the queue.
     *
     * @param  int   $delay
     * @return void
     */
    public function release($delay = 0)
    {
        // SQS job releases are handled by the server configuration...
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return 1;
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job['msg_id'];
    }

    /**
     * Get the IoC container instance.
     *
     * @return \Illuminate\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the underlying Bcms client instance.
     *
     * @return \Chekun\Bcms\Bcms
     */
    public function getBcms()
    {
        return $this->bcms;
    }

    /**
     * Get the underlying raw Bcms job.
     *
     * @return array
     */
    public function getBcmsJob()
    {
        return $this->job;
    }

}