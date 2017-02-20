namespace Modules\Sensors\Listeners;

use Modules\Sensors\Events\Event;
use Illuminate\Queue\SerializesModels;


class SensorEventListener{


	public function handle(Sensorevent $event)
	{
		//whatever needs to be done on the server side
	}


}
