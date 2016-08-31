<?php
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Formatter\LineFormatter;

return [
    'fallback' => 'stream',

    'formatters' => [
        'line_formatter' => new LineFormatter(),
    ],

    'handlers' => [
        'gelf' => [
            'enabled' => false,
            'driver' => 'gelf',
            'host' => 'graylog.absolute.local',
            'transport' => 'udp',
            'port' => 12201,
            'level' => Logger::DEBUG,
        ],
        'stream' => [
            'enabled' => false,
            'driver' => 'stream',
            'path' => storage_path('logs/laravel.log'),
            'level' => Logger::DEBUG,
        ],
        'daily' => [
            'enabled' => true,
            'driver' => 'rotating_file',
            'path' => storage_path('logs/laravel.log'),
            'max_files' => 7,
            'level' => Logger::DEBUG,
            'formatter' => 'line_formatter',
        ],
        'syslog' => [
            'enabled' => false,
            'driver' => 'syslog',
            'ident' => 'laravel',
            'level' => Logger::DEBUG,
        ],
        'errorlog' => [
            'enabled' => false,
            'driver' => 'error_log',
            'message_type' => ErrorLogHandler::OPERATING_SYSTEM,
            'level' => Logger::DEBUG,
        ],
        'loggly' => [
            'enabled' => false,
            'driver' => 'loggly',
            'token' => null,
            'level' => Logger::DEBUG,
        ],
        'mandrill' => [
            'enabled' => false,
            'driver' => 'mandrill',
            'api_key' => null,
            'message' => new Swift_Message('Laravel Log'),
            'level' => Logger::DEBUG,
        ],
        'mongodb' => [
            'enabled' => false,
            'driver' => 'mongo_db',
            'host' => 'localhost',
            'port' => 27017,
            'database' => 'logs',
            'collection' => env('APP_ENV', 'prod'),
            'level' => Logger::DEBUG,
        ],
        'mail' => [
            'enabled' => false,
            'driver' => 'native_mailer',
            'to' => null,
            'subject' => 'Laravel Log',
            'from' => null,
            'level' => Logger::DEBUG,
        ],
    ],

    'processors' => [
        'web' => WebProcessor::class,
    ],
];
