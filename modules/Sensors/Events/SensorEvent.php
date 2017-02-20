<?php

namespace Modules\Sensors\Events;

use Modules\Sensors\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SensorEvent extends Event implements ShouldBroadcast
{

    use SerializesModels;
    public $data;

    public function __construct()
    {

        $this->data = array(
            'nodeId' => '10'
        );
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['sensor-channel'];
    }

    public function onQueue()
    {
        //Broadcast on this queue
        return 'broadcast';
    }

}
