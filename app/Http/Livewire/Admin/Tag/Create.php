<?php

namespace App\Http\Livewire\Admin\Tag;

use App\Http\Livewire\Admin\Tag\Trait\Reactive;
use App\Interface\Repository\ModelRepositoryInterface;
use App\Interface\Services\ImageServiceInterface;
use App\Models\Tag;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    use InteractsWithBanner;
    use AuthorizesRequests;
    use Reactive;

    // used by blade / alpinejs
    /**
     * @var
     */
    public $modalId;

    /**
     * @var bool
     */
    public bool $isModalOpen;

    /**
     * @var bool
     */
    public bool $hasSmallButton;

    // inputs
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $slug;

    /**
     * @var string|null
     */
    public ?string $cover_image_url;

    /**
     * @var array|string[]
     */
    protected array $rules = [
        'name' => 'required|string|min:1|max:255',
        'slug' => 'required|string|unique:tags',
        'cover_image_url' => 'nullable|string',
    ];


    /**
     * @var ModelRepositoryInterface
     */
    private ModelRepositoryInterface $tagRepository;


    /**
     * @var ImageServiceInterface
     */
    private ImageServiceInterface $imageService;


    /**
     * @param ModelRepositoryInterface $tagRepository
     * @param ImageServiceInterface $imageService
     */
    public function boot(ModelRepositoryInterface $tagRepository, ImageServiceInterface $imageService)
    {
        $this->tagRepository = $tagRepository;
        $this->imageService = $imageService;
    }


    /**
     * @param string $modalId
     * @param bool $hasSmallButton
     * @return void
     */
    public function mount(string $modalId, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->initialize();
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function render()
    {
        return view('admin.livewire.tag.create');
    }


    /**
     * Creates one tag
     *
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createTag(): void
    {
        $this->authorize('create', Tag::class);

        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () {
                $tag = [];
                $tag['name'] = $this->name;
                $tag['slug'] = $this->slug;

                if (isset($this->cover_image_url)) {
                    $tag['cover_image_url'] = $this->imageService->getImageAbsolutePath($this->cover_image_url);
                }

                $this->tagRepository->createEntity('Tag', $tag);
            },
            2
        );

        $this->banner(__('New tag successfully added.'));
        $this->initialize();
        $this->rerenderList();
        $this->triggerOnAlert();
    }

}
