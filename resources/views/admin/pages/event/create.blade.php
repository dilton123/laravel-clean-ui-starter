@extends('admin.layouts.admin-nosidebar')

@section('head')
    <x-admin::head.tinymce-config/>
@endsection

@push('head-extra')
    <link href="{{ url('assets/tom-select/tom-select-2.2.2.css') }}" rel="stylesheet">

@endpush

@section('content')
    <main class="padding-1">
        <form action="{{ route('event.store') }}"
              method="POST"
              enctype="application/x-www-form-urlencoded"
              accept-charset="UTF-8"
              autocomplete="off"
        >
            @method("POST")
            @csrf

            <div class="row-padding margin-top-0">

                <div class="col s12 m12 l7" style="padding-right: 1em">

                    <a href="{{ route('event.manage') }}" class="button fs-14 primary alt margin-bottom-1">
                        <i class="fa-solid fa-angles-left"></i>
                        {{ __('Back') }}
                    </a>

                    <h1 class="margin-0 h2">
                        {{ __('Create new event') }}
                    </h1>

                    <x-global::validation-errors/>

                    <!-- Title -->
                    <label for="title" class="bold">{{ __('Title') }}<span
                            class="text-red">*</span></label>
                    <input id="title"
                           class="{{ $errors->has('title') ? ' border border-red' : '' }}"
                           type="text"
                           name="title"
                           autofocus
                           value="{{ old('title') ?? '' }}"
                    />
                    <x-global::input-error for="title"/>


                    <!-- Slug -->
                    <label for="slug" class="bold">{{ __('Slug') }}</label>
                    <input id="slug"
                           class="{{ $errors->has('slug') ? ' border border-red' : '' }}"
                           type="text"
                           name="slug"
                           value="{{ old('slug') ?? '' }}"
                           placeholder="{{ __('(auto-generated from the title, or add a custom slug)') }}"
                    />
                    <x-global::input-error for="slug"/>

                    <!-- Description / text editor -->
                    <label for="description" class="bold">{{ __('Description') }}<span
                            class="text-red">*</span></label>
                    <div>
                         <textarea name="description" rows="5" id="content"
                                   class="{{ $errors->has('description') ? 'border border-red' : '' }}"
                         >{!! old('description') ?? '' !!}
                         </textarea>
                    </div>
                    <div
                        class="{{ $errors->has('description') ? 'error-message' : 'red' }}">
                        {{ $errors->has('description') ? $errors->first('description') : '' }}
                    </div>

                </div>


                <div class="col s12 m12 l5">
                    <div>
                        <!-- Start date -->
                        <label for="start" class="bold">{{ __('Start date') }}<span
                                class="text-red">*</span></label>
                        <input id="start"
                               class="{{ $errors->has('start') ? ' border border-red' : '' }}"
                               type="datetime-local"
                               name="start"
                               value="{{ old('start') ?? '' }}"
                        />

                        <div class="{{ $errors->has('start') ? 'error-message' : '' }}">
                            {{ $errors->has('start') ? $errors->first('start') : '' }}
                        </div>
                    </div>

                    <div>
                        <!-- End date -->
                        <label for="end" class="bold">{{ __('End date') }}<span class="text-red">*</span></label>

                        <input id="end"
                               class="{{ $errors->has('end') ? ' border border-red' : '' }}"
                               type="datetime-local"
                               name="end"
                               value="{{ old('end') ?? '' }}"
                        />

                        <div class="{{ $errors->has('end') ? 'error-message' : '' }}">
                            {{ $errors->has('end') ? $errors->first('end') : '' }}
                        </div>
                    </div>


                    <label class="bold">{{ __('Select the timezone') }}<span class="text-red">*</span>
                    </label>

                    <select
                        class="{{ $errors->has('timezone') ? 'border border-red' : '' }}"
                        aria-label="{{ __("Select the timezone") }}"
                        name="timezone"
                        id="timezone"
                    >
                        @foreach ($timezoneIdentifiers as $key => $value)
                            @if ($value === 'Europe/Budapest')
                                <option selected name="timezone" value="{{ $value }}">{{ $value }}</option>
                            @else
                                <option name="timezone" value="{{ $value }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>

                    <div class="{{ $errors->has('timezone') ? 'error-message' : '' }}">
                        {{ $errors->has('timezone') ? $errors->first('timezone') : '' }}
                    </div>


                    <!-- Is event all day? -->
                    <label for="allDay">{{ __('Is it multi-day?') }}<span class="text-red">*</span></label>
                    <input type="checkbox" id="allDay" name="allDay" value="1">
                    <div class="{{ $errors->has('allDay') ? 'error-message' : '' }}">
                        {{ $errors->has('allDay') ? $errors->first('allDay') : '' }}
                    </div>


                    <!-- Event status -->
                    <label for="status">{{ __('Event status') }}<span class="text-red">*</span></label>
                    <select
                        class="{{ $errors->has('status') ? 'border border-red' : '' }}"
                        name="status"
                        id="status"
                    >
                        @isset($statuses)
                            @foreach ($statuses as $key => $value)
                                <option name="status" {{ $key === 'posted' ? 'selected' : '' }}
                                value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        @endisset
                    </select>

                    <div class="{{ $errors->has('status') ? 'error-message' : '' }}">
                        {{ $errors->has('status') ? $errors->first('status') : '' }}
                    </div>

                    <hr>

                    <!-- Organizer -->
                    <label class="bold">{{ __('Select organizer') }}<span class="text-red">*</span>
                    </label>

                    <select
                        class="{{ $errors->has('organizer_id') ? 'border border-red' : '' }}"
                        aria-label="{{ __("Select the organizer") }}"
                        name="organizer_id"
                        id="organizer_id"
                    >
                        <option selected>{{ __("Select the organizer") }}</option>
                        @isset($organizers)
                            @foreach ($organizers as $organizer)
                                <option name="organizer_id"
                                        value="{{ $organizer->id }}">{{ $organizer->name }}</option>
                            @endforeach
                        @endisset
                    </select>

                    <div class="{{ $errors->has('organizer_id') ? 'error-message' : '' }}">
                        {{ $errors->has('organizer_id') ? $errors->first('organizer_id') : '' }}
                    </div>

                    <hr>

                    <!-- Location -->
                    <label class="bold">{{ __('Select the location') }}<span class="text-red">*</span>
                    </label>

                    <select
                        class="{{ $errors->has('location_id') ? 'border border-red' : '' }}"
                        aria-label="{{ __("Select the location") }}"
                        name="location_id"
                        id="location_id"
                    >
                        <option selected>{{ __("Select the location") }}</option>
                        @isset($locations)
                            @foreach ($locations as $location)
                                <option name="location_id"
                                        value="{{ $location->id }}">{{ $location->name . ', ' . $location->address }}</option>
                            @endforeach
                        @endisset
                    </select>

                    <div class="{{ $errors->has('location_id') ? 'error-message' : '' }}">
                        {{ $errors->has('location_id') ? $errors->first('location_id') : '' }}
                    </div>


                    <hr>

                    <!-- Facebook event url -->
                    <label for="facebook_url" class="bold">{{ __('Facebook event link') }}<span
                            class="text-red">*</span></label>
                    <input id="facebook_url"
                           class="{{ $errors->has('facebook_url') ? ' border border-red' : '' }}"
                           type="url"
                           name="facebook_url"
                           value="{{ old('facebook_url') ?? '' }}"
                    />

                    <x-global::input-error for="facebook_url"/>


                    <!-- Purchase tickets url -->
                    <label for="tickets_url" class="bold">{{ __('Purchase tickets link') }}</label>
                    <input id="tickets_url"
                           class="{{ $errors->has('tickets_url') ? ' border border-red' : '' }}"
                           type="url"
                           name="tickets_url"
                           value="{{ old('tickets_url') ?? '' }}"
                    />

                    <x-global::input-error for="tickets_url"/>


                    <hr>
                    <div class="mb-5">
                        <!-- Cover image -->
                        <label for="cover_image_url" class="bold">{{ __('Cover Image') }}<span
                                class="text-red">*</span></label>

                        <img src="{{ asset('/images/placeholder.png') }}" id="holder" alt="{{ __('Cover image') }}"
                             class="card card-4 image-preview"/>

                        <div class="flex flex-row flex-nowrap margin-top-1 margin-bottom-2">

                            <div>
                                <a id="lfm" data-input="cover_image_url" data-preview="holder"
                                   class="button info margin-top-0">
                                    <i class="fa-solid fa-image"></i> {{ __('Choose') }}
                                </a>
                            </div>

                            <input id="cover_image_url"
                                   class="small-input {{ $errors->has('cover_image_url') ? ' border border-red' : '' }}"
                                   type="text"
                                   readonly
                                   name="cover_image_url"
                                   value="{{ old('cover_image_url') ?? '' }}"
                            />

                        </div>

                        <div id="holder" class="card card-4" style="width: fit-content"></div>
                        <x-global::input-error for="cover_image_url"/>
                    </div>


                    <!-- Cover image -->
                    <label for="backgroundColor">{{ __('Background color (optional)') }}</label>
                    <input type="color"
                           id="backgroundColor"
                           name="backgroundColor"
                           value="{{ old('backgroundColor') ?? '' }}"
                    >

                    <div class="{{ $errors->has('backgroundColor') ? 'error-message' : '' }}">
                        {{ $errors->has('backgroundColor') ? $errors->first('backgroundColor') : '' }}
                    </div>


                    <!-- Background Color Dark -->
                    <label for="backgroundColorDark">{{ __('Background color dark (optional)') }}</label>
                    <input type="color"
                           id="backgroundColorDark"
                           name="backgroundColorDark"
                           value="{{ old('backgroundColorDark') ?? '' }}"
                    >

                    <div class="{{ $errors->has('backgroundColorDark') ? 'error-message' : '' }}">
                        {{ $errors->has('backgroundColorDark') ? $errors->first('backgroundColorDark') : '' }}
                    </div>


                    <hr>

                    <div>
                        <button type="submit" class="primary">{{ __("Create") }}
                        </button>

                        <a href="{{ route('event.manage')}}"
                           class="button alt primary">{{ __('Cancel') }}</a>
                    </div>


                </div>

            </div>
        </form>
    </main>

@endsection

@push('scripts')
    <script src="{{ url('assets/jquery/jquery-3.7.1.js') }}"></script>
    <script src="{{ url('assets/switcher/jquery.simpleswitch.js') }}"></script>
    <script src="{{ url('assets/tom-select/tom-select-2.2.2.js') }}"></script>
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener("DOMContentLoaded", function () {

            document.getElementById('lfm').addEventListener('click', (event) => {
                event.preventDefault();
                window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
            });
        });

        // set file link
        function fmSetLink($url) {
            document.getElementById('cover_image_url').value = $url;
        }

        jQuery(document).ready(function ($) {
            // Switcher
            $('#allDay').simpleSwitch();

            new TomSelect("#timezone", {});
            new TomSelect("#location_id", {});
            new TomSelect("#organizer_id", {});
        });
    </script>
@endpush
