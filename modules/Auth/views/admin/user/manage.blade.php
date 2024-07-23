@extends('admin.layouts.admin')

@section('content')

    <main class="padding-1">
        <nav class="breadcrumb breadcrumb-left">
            <ol>
                <li>
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li>
                    <i class="fa-solid fa-angle-right"></i>
                </li>
                <li>{{ __('Manage Users') }}</li>
            </ol>
        </nav>

        <h1 class="h3 margin-top-bottom-0">{{ __('Manage users') }}</h1>

        <div class="main-content">

            <!-- Create new user -->
            <livewire:admin.auth.user.create title="{{ __('New user') }}"
                                  :roles="$roles"
                                  :hasSmallButton="false"
                                  :modalId="'m-create-user'">
            </livewire:admin.auth.user.create>

            <table>
                <thead>
                <tr class="fs-14">
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><b>{{ $user->name }}</b></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ isset($user->role) ? $user->role->name : '' }}</td>
                        <td>
                            <div class="flex flex-row">

                                @if(auth()->user()->hasRoles('super-administrator') )

                                    <!-- Superadmins cannot be deleted or edited -->
                                    @if (!$user->hasRoles('super-administrator'))
                                    <!-- Delete user -->
                                    <livewire:admin.auth.user.delete title="{{ __('Delete user') }}"
                                                          :user="$user"
                                                          :hasSmallButton="false"
                                                          :modalId="'m-delete-user-' . $user->id"
                                    >
                                    </livewire:admin.auth.user.delete>

                                    <!-- Update user -->
                                    <livewire:admin.auth.user.edit title="{{ __('Edit user') }}"
                                                        :user="$user"
                                                        :roles="$roles"
                                                        :hasSmallButton="false"
                                                        :modalId="'m-edit-user-' . $user->id"
                                    >
                                    </livewire:admin.auth.user.edit>
                                    @endif

                                @else
                                    <p class="italic">{{ __('Super-admin cannot be deleted or edited here.') }}</p>
                                @endif
                            </div>

                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </main>
@endsection
