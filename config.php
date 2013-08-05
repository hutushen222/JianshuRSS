<?php

return array(
    'templates.path' => ROOT . DS . 'templates',

    'debug' => false,

    'log.level' => 4,
    'log.enabled' => true,
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(
        array(
            'path' => ROOT . DS. 'storage/logs',
            'name_format' => 'y-m-d',
        ))
);
