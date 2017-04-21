<?php

namespace Astrotomic\MonologConfig;

use Predis\Client;
use Gelf\Publisher;
use Monolog\Logger;
use Illuminate\Support\Str;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\UdpTransport;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\IFTTTHandler;
use Monolog\Handler\RedisHandler;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\HipChatHandler;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\MandrillHandler;
use Monolog\Handler\SlackbotHandler;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Formatter\FormatterInterface;

class MonologConfigurator
{
    protected $monolog;

    protected $config;

    public function __construct(Logger $monolog)
    {
        $this->monolog = $monolog;
        $this->config = config('monolog');
    }

    public function run()
    {
        $fallback = true;
        foreach ($this->config['handlers'] as $config) {
            if (array_get($config, 'enabled', false)) {
                $fallback = $this->pushHandler($config['driver'], $config) ? false : $fallback;
            }
        }

        if ($fallback) {
            $handler = $this->config['fallback'];
            $config = array_get($this->config['handlers'], $handler);
            $this->pushHandler($config['driver'], $config);
        }

        foreach ($this->config['processors'] as $processor) {
            $this->pushProcessor($processor);
        }
    }

    protected function pushProcessor($processor)
    {
        if (is_string($processor) && class_exists($processor)) {
            $processor = new $processor();
        }
        $this->monolog->pushProcessor($processor);
    }

    protected function pushHandler($handler, array $config)
    {
        $method = $method = 'get'.Str::studly($handler).'Handler';
        if (method_exists($this, $method)) {
            try {
                $handler = $this->$method($config);
                if ($handler instanceof AbstractHandler) {
                    $this->monolog->pushHandler($handler);
                    if (array_key_exists('formatter', $config)) {
                        $formatter = array_get($this->config['formatters'], $config['formatter']);
                        if ($formatter instanceof FormatterInterface) {
                            $handler->setFormatter($formatter);
                        }
                    }
                }

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * @param array $config
     * @return GelfHandler
     * @since v1.0
     */
    protected function getGelfHandler(array $config)
    {
        switch (strtolower($config['transport'])) {
            case 'tcp':
                $transport = new TcpTransport($config['host'], $config['port']);
                break;
            default:
            case 'udp':
                $transport = new UdpTransport($config['host'], $config['port']);
                break;
        }
        $publisher = new Publisher($transport);

        return new GelfHandler($publisher, $config['level']);
    }

    /**
     * @param array $config
     * @return StreamHandler
     * @since v1.0
     */
    protected function getStreamHandler(array $config)
    {
        return new StreamHandler($config['path'], $config['level']);
    }

    /**
     * @param array $config
     * @return RotatingFileHandler
     * @since v1.0
     */
    protected function getRotatingFileHandler(array $config)
    {
        return new RotatingFileHandler($config['path'], $config['max_files'], $config['level']);
    }

    /**
     * @param array $config
     * @return SyslogHandler
     * @since v1.0
     */
    protected function getSyslogHandler(array $config)
    {
        return new SyslogHandler($config['ident'], LOG_USER, $config['level']);
    }

    /**
     * @param array $config
     * @return ErrorLogHandler
     * @since v1.0
     */
    protected function getErrorLogHandler(array $config)
    {
        return new ErrorLogHandler($config['message_type'], $config['level']);
    }

    /**
     * @param array $config
     * @return LogglyHandler
     * @since v1.0
     */
    protected function getLogglyHandler(array $config)
    {
        return new LogglyHandler($config['token'], $config['level']);
    }

    /**
     * @param array $config
     * @return MandrillHandler
     * @since v1.0
     */
    protected function getMandrillHandler(array $config)
    {
        return new MandrillHandler($config['api_key'], $config['message'], $config['level']);
    }

    /**
     * @param array $config
     * @return MongoDBHandler
     * @since v1.0
     */
    protected function getMongoDbHandler(array $config)
    {
        $connection = 'mongodb://'.$config['host'].':'.$config['port'];
        $mongodb = new \MongoDB\Client($connection);

        return new MongoDBHandler($mongodb, $config['database'], $config['collection'], $config['level']);
    }

    /**
     * @param array $config
     * @return NativeMailerHandler
     * @since v1.0
     */
    protected function getNativeMailerHandler(array $config)
    {
        return new NativeMailerHandler($config['to'], $config['subject'], $config['from'], $config['level']);
    }

    /**
     * @param array $config
     * @return NullHandler
     * @since v1.1
     */
    protected function getNullHandler(array $config)
    {
        return new NullHandler($config['level']);
    }

    /**
     * @param array $config
     * @return HipChatHandler
     * @since v1.1
     */
    protected function getHipChatHandler(array $config)
    {
        return new HipChatHandler($config['token'], $config['room'], $config['name'], $config['notify'], $config['level'], true, true, $config['format'], $config['host'], $config['version']);
    }

    /**
     * @param array $config
     * @return IFTTTHandler
     * @since v1.1
     */
    protected function getIftttHandler(array $config)
    {
        return new IFTTTHandler($config['event'], $config['secret_key'], $config['level']);
    }

    /**
     * @param array $config
     * @return LogEntriesHandler
     * @since v1.1
     */
    protected function getLogEntriesHandler(array $config)
    {
        return new LogEntriesHandler($config['token'], true, $config['level']);
    }

    /**
     * @param array $config
     * @return RedisHandler
     * @since v1.1
     */
    protected function getRedisHandler(array $config)
    {
        $client = new Client([
            'scheme' => $config['scheme'],
            'host'   => $config['host'],
            'port'   => $config['port'],
        ]);

        return new RedisHandler($client, $config['key'], $config['level']);
    }

    /**
     * @param array $config
     * @return ZendMonitorHandler
     * @since v1.1
     */
    protected function getZendMonitorHandler(array $config)
    {
        return new ZendMonitorHandler($config['level']);
    }

    /**
     * @param array $config
     * @return SlackHandler
     * @since v1.3
     */
    protected function getSlackHandler(array $config)
    {
        return new SlackHandler(
            $config['token'],
            $config['channel'],
            $config['username'],
            $config['use_attachment'],
            $config['icon_emoji'],
            $config['level'],
            true,
            $config['use_short_attachment'],
            $config['include_context_extra'],
            $config['exclude_fields']
        );
    }

    /**
     * @param array $config
     * @return SlackWebhookHandler
     * @since v1.3
     */
    protected function getSlackWebhookHandler(array $config)
    {
        return new SlackWebhookHandler(
            $config['webhook'],
            $config['channel'],
            $config['username'],
            $config['use_attachment'],
            $config['icon_emoji'],
            $config['use_short_attachment'],
            $config['include_context_extra'],
            $config['level'],
            true,
            $config['exclude_fields']
        );
    }

    /**
     * @param array $config
     * @return SlackbotHandler
     * @since v1.3
     */
    protected function getSlackBotHandler(array $config)
    {
        return new SlackbotHandler(
            $config['team'],
            $config['token'],
            $config['channel'],
            $config['level'],
            true
        );
    }
}
