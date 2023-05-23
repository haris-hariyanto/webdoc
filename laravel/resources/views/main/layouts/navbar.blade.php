<div class="mb-2">
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container">
    
            <a href="{{ route('index') }}" class="navbar-brand">{{ $__appName }}</a>
    
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mt-2 mt-md-0">
                    <li class="nav-item">
                        <a 
                            href="{{ route('index') }}" 
                            @class(['nav-link', 'active' => Route::currentRouteName() === 'index', 'px-2'])
                            @if (Route::currentRouteName() === 'index') aria-current="page" @endif
                        >{{ __('Home') }}</a>
                    </li>
                </ul>
    
                @if (Route::currentRouteName() != 'index')
                    <!-- Desktop search bar -->
                    <div class="mx-3 flex-fill d-none d-md-block">
                        <form action="{{ route('search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search documents" aria-label="Search documents" aria-describedby="searchButtonNavbarDesktop" name="q" value="{{ request()->query('q') }}">
                                <button type="submit" class="btn btn-secondary px-5" id="searchButtonNavbarDesktop">{{ __('Search') }}</button>
                            </div>
                        </form>
                    </div>
                    <!-- [END] Desktop search bar -->
                @endif
    
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ Auth::user()->username }}</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="{{ route('admin.index') }}" class="dropdown-item" target="_blank">{{ __('Dashboard') }}</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a href="{{ route('account.account-settings.index') }}" class="dropdown-item">{{ __('Account Settings') }}</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li x-data>
                                    <form action="{{ route('logout') }}" method="POST" x-ref="formLogout">
                                        @csrf
                                        <a href="{{ route('logout') }}" class="dropdown-item" @click.prevent="$refs.formLogout.submit()">{{ __('Logout') }}</a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
    
        </div>
    </nav>
    
    @if (Route::currentRouteName() != 'index')
        <!-- Search bar mobile -->
        <div class="bg-primary d-block d-md-none">
            <div class="container">
                <form action="{{ route('search') }}" method="GET" class="pt-1 pb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search documents" aria-label="Search documents" aria-describedby="searchButtonNavbarMobile" name="q" value="{{ request()->query('q') }}">
                        <button type="submit" class="btn btn-secondary px-4" id="searchButtonNavbarMobile">{{ __('Search') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- [END] Search bar mobile -->
    @endif
</div>
