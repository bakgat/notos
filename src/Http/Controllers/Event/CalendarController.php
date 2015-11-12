<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 30/09/15
 * Time: 14:46
 */

namespace Bakgat\Notos\Http\Controllers\Event;


use Bakgat\Notos\Domain\Services\Event\CalendarService;
use Bakgat\Notos\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /** @var CalendarService $calendarService */
    private $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        parent::__construct();
        $this->calendarService = $calendarService;
    }

    public function index($orgId)
    {
        $events = $this->calendarService->all($orgId);
        return $this->jsonResponse($events, ['list']);
    }

    public function edit($orgId, $id)
    {
        $event = $this->calendarService->eventOfId($id);
        return $this->jsonResponse($event, ['detail']);
    }

    public function update($orgId, $id, Request $request)
    {
        $data = $request->all();
        $event = $this->calendarService->update($id, $data);
        return $this->jsonResponse($event, ['detail']);
    }

    public function store($orgId, Request $request)
    {
        $data = $request->all();
        $event = $this->calendarService->add($orgId, $data);
        return $this->jsonResponse($event, ['detail']);
    }

    public function destroy($id)
    {
        $this->calendarService->remove($id);
        return $this->destroyedResponse();
    }

    public function between($orgId, $start, $end)
    {
        $dt_start = new DateTime($start);
        $dt_end = new DateTime($end);
        $events = $this->calendarService->eventsBetween($orgId, $dt_start, $dt_end);
        return $this->jsonResponse($events, ['list']);
    }

    public function ofGroup($orgId, $groupId)
    {
        $events = $this->calendarService->eventsOfGroup($groupId);
        return $this->jsonResponse($events, ['list']);
    }

}