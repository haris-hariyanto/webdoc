<!-- Main sidebar container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand logo -->
    <a href="{{ route('admin.index') }}" class="brand-link">
        <img src="{{ asset('assets/admin/images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $__appName }}</span>
    </a>
    <!-- [END] Brand logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!--
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.index'])>
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                -->

                <li class="nav-header">{{ strtoupper(__('Contents')) }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.documents.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.documents.index'])>
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>{{ __('Documents') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ strtoupper(__('Settings')) }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.disks.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.disks.index'])>
                        <i class="nav-icon fas fa-hdd"></i>
                        <p>{{ __('Disks') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.website') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.website'])>
                        <i class="nav-icon fas fa-code"></i>
                        <p>{{ __('Website') }}</p>
                    </a>
                </li>

                <li @class(['nav-item', 'menu-open' => in_array(Route::currentRouteName(), ['admin.settings.home', 'admin.settings.popular-documents', 'admin.settings.new-documents', 'admin.settings.document', 'admin.settings.search', 'admin.settings.sitemap'])])>
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>{{ __('Pages') }} <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.home') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.home'])>
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>{{ __('Home') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.settings.popular-documents') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.popular-documents'])>
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>{{ __('Top Documents') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.settings.new-documents') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.new-documents'])>
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>{{ __('New Documents') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.settings.document') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.document'])>
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>{{ __('Document') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.settings.search') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.search'])>
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>{{ __('Search Page') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.settings.sitemap') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.sitemap'])>
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>{{ __('Sitemap') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">{{ strtoupper(__('Scrapers')) }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.scrapers.proxy') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.scrapers.proxy'])>
                        <i class="nav-icon fas fa-list"></i>
                        <p>{{ __('Proxies') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.scrapers.scribd') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.scrapers.scribd'])>
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>{{ __('Scribd') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.keywords.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.keywords.index'])>
                        <i class="nav-icon fas fa-clock"></i>
                        <p>{{ __('Scrape Queues') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ strtoupper(__('Others')) }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.users.index'])>
                        <i class="nav-icon fas fa-user"></i>
                        <p>{{ __('Users') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.pages.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.pages.index'])>
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>{{ __('Pages') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.contacts.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.contacts.index'])>
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>{{ __('Messages') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- [END] Sidebar menu -->
    </div>
    <!-- [END] Sidebar -->
</aside>
<!-- [END] Main sidebar container -->