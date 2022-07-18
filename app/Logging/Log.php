<?php
namespace App\Logging;
use Monolog\Logger as Monolog;
class Log
{
  /**
   * Create a custom Monolog instance.
   *
   * @param  array $config
   *
   * @return \Monolog\Logger
   */
  public function __invoke(array $config)
  {
    $logger = new Monolog('database');
    
    $logger->pushHandler(new LogHandler());
    $logger->pushProcessor(new LogProcessor());
    return $logger;
  }
}