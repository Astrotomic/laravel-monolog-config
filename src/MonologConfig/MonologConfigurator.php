<?php
namespace Gummibeer\MonologConfig;

use Gelf\Publisher;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\UdpTransport;
use Illuminate\Support\Str;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Handler\MandrillHandler;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;

class MonologConfigurator
{
    protected $monolog;

    protected $config;

    public function __construct(Logger $monolog)
    {
        $this->monolog = $monolog;
        $this->config = config('log');
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
        $method = $method = 'get' . Str::studly($handler) . 'Handler';
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
    }

    protected function getGelfHandler(array $config)
    {
        if ($config['transport'] == 'tcp') {
            $transport = new TcpTransport($config['host'], $config['port']);
        } else {
            $transport = new UdpTransport($config['host'], $config['port']);
        }
        $publisher = new Publisher($transport);
        return new GelfHandler($publisher, $config['level']);
    }

    protected function getStreamHandler(array $config)
    {
        return new StreamHandler($config['path'], $config['level']);
    }

    protected function getRotatingFileHandler(array $config)
    {
        return new RotatingFileHandler($config['path'], $config['max_files'], $config['level']);
    }

    protected function getSyslogHandler(array $config)
    {
        return new SyslogHandler($config['ident'], LOG_USER, $config['level']);
    }

    protected function getErrorLogHandler(array $config)
    {
        return new ErrorLogHandler($config['message_type'], $config['level']);
    }

    protected function getLogglyHandler(array $config)
    {
        return new LogglyHandler($config['token'], $config['level']);
    }

    protected function getMandrillHandler(array $config)
    {
        return new MandrillHandler($config['api_key'], $config['message'], $config['level']);
    }

    protected function getMongoDbHandler(array $config)
    {
        $connection = 'mongodb://' . $config['host'] . ':' . $config['port'];
        $mongodb = new \Mongo($connection);
        return new MongoDBHandler($mongodb, $config['database'], $config['collection'], $config['level']);
    }

    protected function getNativeMailerHandler(array $config)
    {
        return new NativeMailerHandler($config['to'], $config['subject'], $config['from'], $config['level']);
    }
}