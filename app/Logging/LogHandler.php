<?php
namespace App\Logging;
use App\Events\LogMonologEvent;
use Monolog\Logger as Monolog;
use Monolog\Handler\AbstractProcessingHandler;
use App\Models\Log;
class LogHandler extends AbstractProcessingHandler
{
  public function __construct($level = Monolog::DEBUG)
  {
    parent::__construct($level);
  }
  /**
   * Writes the record down to the log of the implementing handler
   *
   * @param  array $record
   *
   * @return void
   */
  protected function write(array $record):void
  {
    // Simple store implementation
    $log = new Log();
    $log->fill($record['formatted']);
    $log->save();
// Queue implementation
    //  event(new LogMonologEvent($record));
  }
  /**
   * {@inheritDoc}
   */
  protected function getDefaultFormatter():LogFormatter
  {
    return new LogFormatter();
  }
}