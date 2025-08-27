<?php
// f:\WebDevelopment\Mytherra\backend\scripts\start-game-loop.php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Config\Database;
use App\Commands\GameLoopWorker;
use Illuminate\Queue\Worker;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize database connection
Database::init();

// Create worker dependencies
$container = new Container();
$events = new Dispatcher($container);
$worker = new Worker($events);

// Create and run the game loop worker
$command = new GameLoopWorker($worker);
$command->handle();
