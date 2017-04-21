<?php

use Monolog\Logger;
use Monolog\Handler\HipChatHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;

return [
    'fallback' => 'stream',

    'formatters' => [
        'line_formatter' => new LineFormatter(),
    ],

    'handlers' => [
        'gelf' => [
            'enabled' => false,
            'driver' => 'gelf',
            'host' => 'localhost',
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
        'devnull' => [
            'enabled' => false,
            'driver' => 'null',
            'level' => Logger::DEBUG,
        ],
        'hipchat' => [
            'enabled' => false,
            'driver' => 'hip_chat',
            'token' => null,
            'room' => null,
            'name' => 'Laravel Log',
            'notify' => true,
            'format' => 'text',
            'host' => 'api.hipchat.com',
            'version' => HipChatHandler::API_V1,
            'level' => Logger::DEBUG,
        ],
        'ifttt' => [
            'enabled' => false,
            'driver' => 'ifttt',
            'event' => null,
            'secret_key' => null,
            'level' => Logger::DEBUG,
        ],
        'logentries' => [
            'enabled' => false,
            'driver' => 'log_entries',
            'token' => null,
            'level' => Logger::DEBUG,
        ],
        'redis' => [
            'enabled' => false,
            'driver' => 'redis',
            'scheme' => 'tcp',
            'host' => 'localhost',
            'port' => 6379,
            'key' => null,
            'level' => Logger::DEBUG,
        ],
        'zend' => [
            'enabled' => false,
            'driver' => 'zend_monitor',
            'level' => Logger::DEBUG,
        ],
        'slack' => [
            'enabled' => false,
            'driver' => 'slack',
            'level' => Logger::DEBUG,
            'token' => '',
            'channel' => null,
            'username' => null,
            'icon_emoji' => null,
            'use_attachment' => true,
            'use_short_attachment' => false,
            'include_context_extra' => false,
            'exclude_fields' => [],
        ],
        'slack_webhook' => [
            'enabled' => false,
            'driver' => 'slack_webhook',
            'level' => Logger::DEBUG,
            'webhook' => '',
            'channel' => null,
            'username' => null,
            'icon_emoji' => null,
            'use_attachment' => true,
            'use_short_attachment' => false,
            'include_context_extra' => false,
            'exclude_fields' => [],
        ],
        'slack_bot' => [
            'enabled' => false,
            'driver' => 'slack_bot',
            'level' => Logger::DEBUG,
            'team' => '',
            'token' => '',
            'channel' => null,
        ],
    ],

    'processors' => [
        'web' => WebProcessor::class,
    ],
];
