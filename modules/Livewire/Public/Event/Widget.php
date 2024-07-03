<?php

namespace Modules\Livewire\Public\Event;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Event\Interfaces\Repositories\EventRepositoryInterface;
use Modules\Event\Models\Event;
use Modules\Event\Models\Location;
use Modules\Event\Models\Organizer;

class Widget extends Component
{
    use WithPagination;

    /**
     * Event list as collection
     *
     * @var LengthAwarePaginator|null
     */
    protected ?LengthAwarePaginator $events;


    /**
     * @var bool
     */
    public bool $show;


    // inputs
    /**
     * @var string
     */
    public string $city;


    /**
     * @var string
     */
    public string $organizerId;


    /**
     * @var array
     */
    public array $cities;


    /**
     * @var array
     */
    public array $organizers;


    /**
     * @var string
     */
    public string $searchTerm;


    /**
     * @var EventRepositoryInterface
     */
    private EventRepositoryInterface $eventRepository;


    /**
     * @param  EventRepositoryInterface  $eventRepository
     * @return void
     */
    public function boot(EventRepositoryInterface $eventRepository): void
    {
        $this->eventRepository = $eventRepository;
    }


    /**
     * @return void
     */
    public function mount(): void
    {
        $this->show = false;
        $this->events = null;
        $this->city = '';
        $this->organizerId = '';
        $this->searchTerm = '';

        $this->cities = Location::select('city')->distinct()->get()->toArray();

        $this->organizers = Organizer::get()->toArray();
    }


    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function render(): Factory|View|\Illuminate\Foundation\Application|Application
    {

        $lastDay = Carbon::today()->subDay();

        if (!isset($this->events)) {
            $this->events = $this->eventRepository->getPaginatedEventsNewerThan($lastDay->toDateString());
        }

        $this->resetPage();

        return view('public.livewire.event.widget')->with([
            'events' => $this->events,
            'dtFormat' => Event::getLocaleDateTimeFormat(),
            'utcTz' => new \DateTimeZone("UTC")
        ]);
    }


    /**
     * @return void
     */
    public function updatedCity(): void
    {
        $this->queryEvents();
        $this->resetPage();

        $this->render();
    }


    /**
     * @return void
     */
    public function updatedOrganizerId(): void
    {
        $this->queryEvents();
        $this->resetPage();

        $this->render();
    }


    /**
     * @return void
     */
    public function search(): void
    {
        $this->queryEvents();
    }


    /**
     * @return void
     */
    private function queryEvents(): void
    {
        $lastDay = Carbon::today()->subDay();
        $organizerId = intval($this->organizerId);

        $this->events = $this->eventRepository->filterEvents($lastDay->toDateString(), $this->city, $organizerId,
            $this->searchTerm);
    }


    /**
     * @return void
     */
    public function resetFilters(): void
    {
        $this->city = '';
        $this->organizerId = 0;
        $this->searchTerm = '';
    }

}
