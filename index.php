<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LogLevel;
use Slim\Factory\AppFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use OpenTelemetry\API\Globals;

require __DIR__ . '/vendor/autoload.php';

require('dice.php');
require('instrumentation.php');

$tracerProvider = Globals::tracerProvider();
$tracer = $tracerProvider->getTracer(
  'dice-server',
  '0.1.0',
  'https://opentelemetry.io/schemas/1.24.0'
);

$tracerProvider = \OpenTelemetry\API\Globals::tracerProvider();
$meterProvider = \OpenTelemetry\API\Globals::meterProvider();
$loggerProvider = \OpenTelemetry\API\Globals::loggerProvider();


$logger = new Logger('dice-server');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

$app = AppFactory::create();

$dice = new Dice();

$app->get('/rolldice', function (Request $request, Response $response) use ($logger, $dice) {
    $params = $request->getQueryParams();
    if(isset($params['rolls'])) {
        $result = $dice->roll($params['rolls']);
        if (isset($params['player'])) {
          $logger->info("A player is rolling the dice.", ['player' => $params['player'], 'result' => $result]);
        } else {
          $logger->info("Anonymous player is rolling the dice.", ['result' => $result]);
        }
        $response->getBody()->write(json_encode($result));
    } else {
        $response->withStatus(400)->getBody()->write("Please enter a number of rolls");
    }
    return $response;
});

$app->run();
