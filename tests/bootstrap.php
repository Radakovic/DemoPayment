<?php

use Symfony\Component\Dotenv\Dotenv;

//require dirname(__DIR__).'/vendor/autoload.php';
//
//if (method_exists(Dotenv::class, 'bootEnv')) {
//    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
//}
//
//if ($_SERVER['APP_DEBUG']) {
//    umask(0000);
//}

if (in_array('--version', $GLOBALS['argv'])) {
    return;
}

require_once(__DIR__ . '/../vendor/autoload.php');

chdir(__DIR__.'/../');

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    // executes the "php bin/console cache:clear" command
    passthru(sprintf(
        'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'],
        __DIR__
    ));
}

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

// Wait until Postgres is ready!
(function () {
    $i = 1;
    $delay = 1;
    $maxTries = 15;

    do {
        echo "[Attempt $i/$maxTries] Waiting $delay seconds for postgres to get ready"
            . PHP_EOL;

        exec(
            "bin/console doctrine:database:create --if-not-exists --env=test",
            $output,
            $exitCode
        );
        echo implode("\n", $output) . "\n";


        if ($exitCode !== 0) {
            echo "[Attempt $i/$maxTries] Postgres not ready" . PHP_EOL;
            $i++;
            sleep($delay);
        }

    } while (0 !== $exitCode && $i <= $maxTries);

    if (0 !== $exitCode) {
        exit ("[Attempt $i/$maxTries] Cannot connect to postgres?");
    }

    echo "[Attempt $i/$maxTries] Postgres ready!" . PHP_EOL;
})();

echo "[Running migrations]".PHP_EOL;

exec('bin/console doctrine:migrations:migrate --env=test -n --allow-no-migration', $exitCode, $status);

if (0 !== $status) {
    echo implode(PHP_EOL, $exitCode);
    exit(1);
}
