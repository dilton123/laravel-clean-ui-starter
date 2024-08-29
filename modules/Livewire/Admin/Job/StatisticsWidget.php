<?php

namespace Modules\Livewire\Admin\Job;

use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Job\Models\Client;
use Modules\Job\Models\Job;

class StatisticsWidget extends Component
{
    use WithPagination;

    /**
     * Paginated Collection of jobs (events)
     * @var
     */
    protected $jobs;


    /**
     * The client you want to generate the statistics for
     *
     * @var int
     */
    public int $clientId;


    /**
     * @var Collection
     */
    public Collection $clients;


    // client name and id for select field
    /**
     * @var array
     */
    public array $clientsData;


    /**
     * The data to pass to the Google Chart library to render
     *
     */
    public Collection|array $chartData;


    // for filtering by date interval
    /**
     * @var string
     */
    public string $startDate;


    /**
     * @var string
     */
    public string $endDate;


    /* Chart option properties */
    /**
     * @var string
     */
    public string $chartTitle;


    /**
     * @var string
     */
    public string $chartId;


    /**
     * @var string
     */
    public string $chartAreaWidth;


    /**
     * @var string
     */
    public string $chartColor;


    /**
     * @var string
     */
    public string $chartXAxisTitle;


    /**
     * @var string
     */
    public string $chartVAxisTitle;


    /**
     * @var
     */
    public $totalJobs;


    /**
     * @var float
     */
    public float $sumOfHours = 0.0;


    /**
     * @var array|array[]
     */
    protected array $rules = [
        'clientId' => ['required', 'int', 'max:255'],
        'startDate' => ['nullable', 'date'],
        'endDate' => ['nullable', 'date'],
    ];


    /**
     * @throws Exception
     */
    public function mount(): void
    {
        $this->jobs = null;
        $this->clientId = 0;
        $this->chartData = [];
        $this->totalJobs = null;

        $firstDayOfTheMonth = new DateTime('first day of this month', new DateTimeZone('Europe/Budapest'));
        $lastDayOfTheMonth = new DateTime('last day of this month', new DateTimeZone('Europe/Budapest'));

        $this->startDate = $firstDayOfTheMonth->format('Y-m-d');
        $this->endDate = $lastDayOfTheMonth->format('Y-m-d');

        $this->clients = Client::all();
        $this->clientsData[__('All')] = 0;

        foreach ($this->clients as $client) {
            $this->clientsData[$client->name] = $client->id;
        }

        $this->chartTitle = __('Hours of jobs done for clients');
        $this->chartId = 'chart_div';
        $this->chartAreaWidth = '65%';
        $this->chartColor = '#13B623';
        $this->chartXAxisTitle = __('Hours of work');
        $this->chartVAxisTitle = __('Client name');
    }


    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     * @throws Exception
     */
    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->queryDataForChart();
        $this->getJobList();

        return view('admin.livewire.job.statistics_widget')->with([
            'jobs' => $this->jobs
        ]);
    }


    /**
     * @throws Exception
     */
    public function getJobList(): void
    {
        // validate user input
        $this->validate();

        $tz = new DateTimeZone('Europe/Budapest');
        $startDate = new DateTime($this->startDate, $tz);
        $endDate = new DateTime($this->endDate, $tz);
        $interval = $startDate->diff($endDate);
        $weeks = (int) floor($interval->days / 7);

        $result = DB::table('jobs')
            ->selectRaw(
                "clients.name,
                        jobs.is_recurring,
                        CASE
                            WHEN (jobs.is_recurring = 0) THEN
                                TIME_FORMAT(ABS(TIMEDIFF(jobs.start, jobs.end)),'%H:%i')
                            WHEN (jobs.is_recurring = 1) THEN
                                TIME_FORMAT(jobs.duration,'%H:%i')
                        END AS durationCalc,

                        CASE
                            WHEN (jobs.is_recurring = 0) THEN
                                TIME_TO_SEC(TIMEDIFF(jobs.end, jobs.start)) / 3600
                            WHEN (jobs.is_recurring = 1) THEN
                                TIME_TO_SEC(jobs.duration) / 3600 * FLOOR( $weeks / JSON_EXTRACT(`rrule` , '$.interval') )
                        END AS hours,
                        jobs.start,
                        jobs.end,
                        jobs.rrule"
            )
            ->leftJoin('clients', 'jobs.client_id', '=', 'clients.id');

        $result = $this->addWhereConditionsToQueries($result);
        $result = $result
            ->orderByDesc('clients.name')
            ->groupBy('clients.name',
                'jobs.is_recurring',
                'durationCalc',
                'hours',
                'jobs.rrule',
                'jobs.start',
                'jobs.end'
            )
            ->paginate(Job::RECORDS_PER_PAGE);

        $this->sumOfHours = (float) $result->sum('hours');
        $this->jobs = $result;
    }


    /**
     * @return void
     * @throws Exception
     */
    public function getResults(): void
    {
        $this->getJobList();
        $this->queryDataForChart();
        $this->resetPage();
    }


    /**
     * @param $query
     *
     * @return mixed
     */
    private function addWhereConditionsToQueries($query): mixed
    {

        if ($this->clientId === 0) {
            $query = $query
                ->whereRaw("jobs.start >= ? AND jobs.start <= ?", [$this->startDate, $this->endDate])
                ->orWhereRaw("DATE(JSON_UNQUOTE(JSON_EXTRACT(jobs.rrule , '$.dtstart'))) >= ? OR DATE(JSON_UNQUOTE(JSON_EXTRACT(jobs.rrule , '$.dtstart'))) <= ?", [$this->startDate, $this->startDate]);
        } else {
            $query = $query
                ->whereRaw("jobs.start >= ? AND jobs.start <= ? AND jobs.client_id = ?",
                    [$this->startDate, $this->endDate, $this->clientId])
                ->orWhereRaw("( DATE(JSON_UNQUOTE(JSON_EXTRACT(jobs.rrule , '$.dtstart'))) >= ? OR DATE(JSON_UNQUOTE(JSON_EXTRACT(jobs.rrule , '$.dtstart'))) <= ? ) AND jobs.client_id = ? ",
                    [$this->startDate, $this->startDate, $this->clientId]);
        }

        return $query;
    }


    /**
     * @throws Exception
     */
    public function queryDataForChart(): void
    {
        // validate user input
        $this->validate();

        $tz = new DateTimeZone('Europe/Budapest');
        $startDate = new DateTime($this->startDate, $tz);
        $endDate = new DateTime($this->endDate, $tz);
        $interval = $startDate->diff($endDate);

        $statement = DB::statement("SET @per_week=0");
        $statistics = DB::table('jobs')
            ->selectRaw(
                "clients.name,
                        @per_week:=(CAST( 7 * JSON_UNQUOTE( JSON_EXTRACT( jobs.rrule, '$.interval' ) ) AS UNSIGNED )),
                            ( CASE
                                WHEN ( jobs.is_recurring = 0 ) THEN
                                    SUM( TIME_TO_SEC( TIMEDIFF( jobs.end, jobs.start ) ) / 3600 )
                                WHEN ( jobs.is_recurring = 1 ) THEN
                                   SUM( TIME_TO_SEC( jobs.duration ) / 3600 * (
                                        WITH RECURSIVE DateRange AS (
                                            SELECT
                                            (CASE
                                                WHEN ? < DATE( REPLACE(REPLACE(JSON_UNQUOTE( JSON_EXTRACT( jobs.rrule, '$.dtstart' ) ), 'T', ' '), 'Z', '') ) THEN
                                                    DATE( REPLACE(REPLACE(JSON_UNQUOTE( JSON_EXTRACT( jobs.rrule, '$.dtstart' ) ), 'T', ' '), 'Z', '') )
                                                ELSE
                                                    ?
                                            END) AS StartDate
                                            UNION ALL
                                            SELECT DATE_ADD( StartDate, INTERVAL @per_week DAY )
                                            FROM DateRange
                                            WHERE StartDate < DATE_ADD( ?, INTERVAL -@per_week DAY )
                                    ) SELECT COUNT( StartDate ) FROM DateRange ) )
                            END ) AS hours", [$this->startDate, $this->startDate, $this->endDate]
            )
            ->leftJoin('clients', 'jobs.client_id', '=', 'clients.id');

        $statistics = $this->addWhereConditionsToQueries($statistics);
        $statistics = $statistics
            ->groupBy('clients.name', 'jobs.is_recurring', 'jobs.rrule')
            ->get();


        $this->chartData = $statistics;
    }
}
