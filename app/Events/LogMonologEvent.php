<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogMonologEvent
{
    use SerializesModels;
  /**
   * @var
   */
  public $records;
  /**
   * @param $model
   */
  public function __construct(array $records)
  {
    $this->records = $records;
}
}
