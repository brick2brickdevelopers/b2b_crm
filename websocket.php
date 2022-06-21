<?php

use Workerman\Worker;
use PHPSocketIO\SocketIO;

require_once __DIR__ . '/vendor/autoload.php';
$context = array(
    'ssl' => array(
        'local_cert'  => '/etc/letsencrypt/live/voc.tdas.in/fullchain.pem',
        'local_pk'    => '/etc/letsencrypt/live/voc.tdas.in/privkey.pem',
        'verify_peer' => false
    )
);

$io = new SocketIO(8080, $context);
$io->on('connection', function ($socket) use ($io) {

    $socket->on('call', function ($msg) use ($io) {
        $io->emit('call', $msg);
    });
    $socket->on('callEnd', function ($msg) use ($io) {
        $io->emit('callEnd', $msg);
    });
    $socket->on('click2call', function ($msg) use ($io) {
        $io->emit('click2call', $msg);
    });
});

Worker::runAll();
