<?php
/**
 * Created by : VsCode
 * User: Dumb Lake Monster (Wang Haiyang)
 * Date:  2023/8/3
 * Time:  10:58
 */

namespace App\Logging;

class RabbitMQMange
{


    private $connection;
    private $channel;

    public  function __construct()
    {
        // 连接到 RabbitMQ
        $this->connection = new \AMQPConnection([
            'host'     => 'localhost',
            'port'     => '5672',
            'login'    => 'guest',
            'password' => 'guest'
        ]);
        $this->connection->connect();
        $this->channel = new \AMQPChannel($this->connection);
    }


    public  function  connect()
    {


    }
    /**
     * @param $exchange_name
     * @param $queue_name
     * @param $message
     * @param $type
     * @return void
     */

    public function produce($message,$queue_name, $exchange_name,$type)
    {
        // 声明交换机
        $exchange = new \AMQPExchange($this->channel);
        $exchange->setName($exchange_name);
        switch ($type){
            case 'AMQP_EX_TYPE_DIRECT':
                $exchange->setType(AMQP_EX_TYPE_DIRECT);
                break;
            default:
                $exchange->setType(AMQP_EX_TYPE_DIRECT);

        }
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();
        // 声明队列
        $queue = new \AMQPQueue($this->channel);
        $queue->setName($queue_name);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();

        // 将队列绑定到交换机
        $queue->bind($exchange_name);

        // 发送消息到交换机
        $exchange->publish($message, '', AMQP_NOPARAM, [
            'delivery_mode' => AMQP_DURABLE
        ]);
    }


}
