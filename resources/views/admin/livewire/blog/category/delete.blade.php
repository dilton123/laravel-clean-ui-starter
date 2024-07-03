<article x-data="{
    isModalOpen: $wire.$entangle('isModalOpen', true)
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="danger" title="{{ __('Delete category') }}">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="danger">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
            <span>{{ __('Delete') }}</span>
        </button>
    @endif

    <x-global::form-modal trigger="isModalOpen" title="{{ __('Are you sure you want to delete it?') }}"
                          id="{{ $modalId }}">
        <form wire:submit="deleteCategory">
            <h2 class="h3">{{ $name }}</h2>
            <hr>
            <label for="categoryId" class="sr-only">{{ __('Category Id') }}</label>
            <input wire:model="categoryId"
                   disabled
                   type="number"
                   class="hidden"
                   name="categoryId"
                   id="categoryId"
            >

            <div class="actions">
                <button type="submit" class="danger">
                    <span wire:loading wire:target="deleteCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="deleteCategory">{{ __('Delete') }}</span>
                </button>
                <button type="button" class="danger alt" @click="isModalOpen = false">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>

    </x-global::form-modal>
</article>
