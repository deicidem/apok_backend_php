<?php

namespace App\Listeners;
use App\Events\LogMonologEvent;
use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
class LogMonologEventListener implements ShouldQueue
{
  public $queue = 'logs';
  protected $log;
  public function __construct(Log $log)
  {
    $this->log = $log;
  }
  /**
   * @param $event
   */
  public function onLog($event)
  {
    $log = new $this->log;
    $log->fill($event->records['formatted']);
    $log->save();
  }
  /**
   * Register the listeners for the subscriber.
   *
   * @param \Illuminate\Events\Dispatcher $events
   */
  public function subscribe($events)
  {
    $events->listen(
      LogMonologEvent::class,
      '\App\Listeners\LogMonologEventListener@onLog'
    );
  }
}