<?php

namespace Modules\Event\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\Auth\Traits\UserPermissions;
use Modules\Clean\Interfaces\Repositories\ModelRepositoryInterface;
use Modules\Event\Interfaces\Entities\OrganizerInterface;
use Modules\Event\Models\Organizer;

class OrganizerController extends Controller
{
    use UserPermissions;

    /**
     * @var ModelRepositoryInterface
     */
    private ModelRepositoryInterface $modelRepository;


    /**
     * @param  ModelRepositoryInterface  $modelRepository
     */
    public function __construct(ModelRepositoryInterface $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }


    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Organizer::class);

        $organizers = $this->modelRepository->paginateEntities('Event\Models\Organizer', OrganizerInterface::RECORDS_PER_PAGE);

        return view('admin.pages.event.organizer.manage')->with([
            'organizers' => $organizers,
            'userPermissions' => $this->getUserPermissions()
        ]);
    }
}
