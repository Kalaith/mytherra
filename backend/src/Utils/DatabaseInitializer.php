<?php

namespace App\Utils;

use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseInitializer
{
    public static function initialize(array $config = null): void
    {
        $capsule = new Capsule;

        // If no config provided, use environment variables
        if ($config === null) {
            $config = [
                'driver'    => $_ENV['DB_CONNECTION'] ?? 'mysql',
                'host'      => $_ENV['DB_HOST'] ?? 'localhost',
                'database'  => $_ENV['DB_DATABASE'] ?? 'mytherra',
                'username'  => $_ENV['DB_USERNAME'] ?? 'root',
                'password'  => $_ENV['DB_PASSWORD'] ?? '',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ];
        }

        $capsule->addConnection($config);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
