<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class LogToRabbitMQ implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * http://:15672/
     * @return void
     */
    public function handle()
    {
        //
        $connection = new AMQPStreamConnection('1.117.159.188','5672','guest','guest');
        $channel = $connection->channel();
        $channel->queue_declare('Loginsert',false,false,false,false);
        $logMessage = 'Your log message here'; // You can fetch the log message from the job data
        $msg = new AMQPMessage($logMessage);

        $channel->basic_publish($msg, '', 'logs');

        $channel->close();
        $connection->close();
    }
}
