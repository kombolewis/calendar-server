<?php

namespace App\Http\Controllers\Calendar;

use DateTime;
use DateInterval;
use App\Models\Events;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Schedules;

class EventsController extends Controller
{
  /**
   * Undocumented function
   *
   * @param Request $request
   * @return Response
   */
  public function store(Request $request) {
		$data = $request->validate([
			'date' => 'required|string',
			'start_time' => 'required|string',
			'end_time' => 'required|string',
			'name' => 'required|string',
			'description' => 'required|string',
			'day_of_the_week' => 'required',
		]);
    $data = $this->storeHelper($data);

    $ok = $this->createSchedule(Events::create($data));
    if($ok){
      return response()->json(['message' => 'Event Created'],201);
    }

    return response()->json(['message' => 'Something went wrong'],500);


    

  }


  /**
   * Array to string conversion function
   *
   * @param mixed $data
   * @return array
   */
  private function storeHelper($data)  :array {
    $data['day_of_the_week'] = join(',',$data['day_of_the_week']);
    return array_merge($data, [
      'user_id' => auth()->user()->id
    ]);
    
  }

  /**
   * Create Schedule from created event
   *
   * @param Events $event
   * @return boolean
   */
  private function createSchedule(Events $event) :bool{
    $days = $this->findDates((object)$event);
    return $this->saveToSchedules($days, (object)$event);

  }

  /**
   * Write the Schedules to db
   *
   * @param array $days
   * @param object $event
   * @return boolean
   */
  private function saveToSchedules(array $days, object $event) :bool {
    foreach($days as $day) {
      $schedule = new Schedules;
      $schedule->events_id = $event->id;
      $schedule->scheduled_date = $day;
      $ok = $schedule->save();
    }
    return $ok;
  }


  /**
   * find future dates
   *
   * @param Events $record
   * @return array
   */
  private function findDates(Events $record) :array{
    $dates = [];

    $dates[] = (new DateTime($record->date))->format('Y-m-d');
    
    for ($i=90; $i > 7;) { 
      if($i == 90){
        $date = new DateTime($record->date);
      }else{
        $date = new DateTime(end($dates));
      }
      $date->add(new DateInterval('P7D'));
      $dates[] = $date->format('Y-m-d');
      $i-=7;
    }
    return $dates;
  }

    
}
