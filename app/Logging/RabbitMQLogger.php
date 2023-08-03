<?php
/**
 * Created by : VsCode
 * User: Dumb Lake Monster (Wang Haiyang)
 * Date:  2023/8/3
 * Time:  10:00
 */

namespace App\Logging;

use App\Jobs\LogToRabbitMQ;
use Illuminate\Foundation\Bus\Dispatchable;
use Monolog\Handler\AbstractProcessingHandler;

class RabbitMQLogger extends AbstractProcessingHandler
{
    use Dispatchable;

    public function write(array $record): void
    {
        $message = $record['formatted'];

        // Dispatch the LogToRabbitMQ job to send the log message to RabbitMQ
        $this->dispatch(new LogToRabbitMQ($message));
    }
}
