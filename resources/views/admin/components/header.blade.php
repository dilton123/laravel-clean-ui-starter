<header class="page-header">
    <section class="header__container">
        <section class="header-content">
            <div class="logo">
                <a href="/" class="brand">
                    <img src="{{ url('/images/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}">
                </a>
            </div>
            @if (Route::has('login'))
                <section class="main-navigation">
                    <nav id="main-menu">
                        @auth
                            <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                               href="{{ url('/admin/dashboard') }}">
                                <i class="fa fa-tachometer" aria-hidden="true"></i>{{ __('Dashboard') }}
                            </a>

                            @if(in_array('manage-posts', $userPermissions))
                                <!-- Manage posts/articles link -->
                                <a class="{{ request()->routeIs('post.manage') ? 'active' : '' }}"
                                   href="{{ route('post.manage') }}"
                                >
                                    <i class="fa-regular fa-newspaper"></i><span>{{ __('Posts') }}</span>
                                </a>
                            @endif


                            @if(in_array('manage-categories', $userPermissions))
                                <!-- Manage categories link -->
                                <a class="{{ request()->routeIs('category.manage') ? 'active' : '' }}"
                                   href="{{ route('category.manage') }}"
                                >
                                    <i class="fa-solid fa-folder-open"></i><span>{{ __('Categories') }}</span>
                                </a>
                            @endif


                            @if(in_array('manage-tags', $userPermissions))
                                <!-- Manage tags link -->
                                <a class="{{ request()->routeIs('tag.manage') ? 'active' : '' }}"
                                   href="{{ route('tag.manage') }}"
                                >
                                    <i class="fa-solid fa-tags"></i><span>{{ __('Tags') }}</span>
                                </a>
                            @endif


                            @if(in_array('manage-events', $userPermissions))
                                <a class="{{ request()->routeIs('event.manage') ? 'active' : '' }}"
                                   href="{{ route('event.manage') }}">
                                    <i class="fa-solid fa-calendar-days"></i><span>{{ __('Events') }}</span>
                                </a>
                            @endif


                            @if(in_array('manage-account', $userPermissions))
                                <section
                                    x-data="dropdownData"
                                    class="dropdown-click"
                                    @click.outside="hideDropdown"
                                    @keydown.enter="toggleDropdown"
                                    aria-label="{{ __('Toggle account dropdown menu') }}"
                                    role="region"
                                    tabindex="0"
                                >
                                    <a class="" @click="toggleDropdown">
                                        <i class="fa fa-user"
                                           aria-hidden="true"></i><span>{{ Auth::user()->name }}</span><i
                                            class="fa fa-caret-down"></i>
                                    </a>

                                    <article
                                        x-show="openDropdown"
                                        x-cloak
                                        class="dropdown-content card padding-1"
                                        aria-label="{{ __('Account dropdown menu') }}"
                                        x-bind:aria-expanded="openDropdown"
                                    >

                                        <a class="dropdown-item"
                                           href="{{ route('user.account', auth()->id()) }}"
                                        >
                                            <i class="fa fa-user"
                                               aria-hidden="true"></i><span>{{ __('My Account') }}</span>
                                        </a>


                                        <a
                                            id="logout-form-admin-header-trigger"
                                            class="dropdown-item margin-bottom-0"
                                            href="#"
                                            role="button"
                                            onclick="triggerLogout('logout-form-admin-header')"
                                        >
                                            <i class="fa fa-sign-out"
                                               aria-hidden="true"></i><span>{{ __('Logout') }}</span>

                                        </a>

                                        <form
                                            id="logout-form-admin-header"
                                            action="{{ route('logout') }}"
                                            method="POST"
                                            class="d-none"
                                        >
                                            @csrf
                                            @method('post')
                                        </form>
                                    </article>
                                </section>
                            @endif

                        @else

                        @endauth

                    </nav>

                    @php
                        $light = __('Light mode');
                        $dark = __('Dark mode');
                    @endphp
                    <button
                        class="darkmode-toggle margin-top-0"
                        rel="button"
                        @click="toggleDarkMode"
                        x-text="isDarkModeOn() ? '🔆' : '🌒'"
                        :title="isDarkModeOn() ? '{{ $light }}' : '{{ $dark }}'"
                    >
                    </button>

                    <div x-data="offCanvasMenuData">
                        <button id="main-menu-offcanvas-toggle"
                                @click="toggleOffcanvasMenu()"
                                class="primary alt margin-left-0-5"
                                data-collapse-toggle="navbar-default"
                                type="button"
                                aria-controls="navbar-default"
                                aria-expanded="false"
                        >
                            <span class="sr-only">{{__('Open main menu')}}</span>
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </button>
                        <div class="sidenav relative"
                             tabindex="-1"
                             id="main-menu-offcanvas"
                             @click.outside="closeOnOutsideClick()"
                        >
                            <a href="#"
                               role="button"
                               aria-label="{{ __('Close sidebar') }}"
                               id="main-menu-close-button"
                               @click="closeOffcanvasMenu()"
                               class="close-btn fs-18 absolute topright padding-0-5"
                            >
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>

                            <div id="mobile-menu"></div>

                        </div>

                    </div>
                </section>
            @endif
        </section>
    </section>
</header>


