<header class="top-header">
    <nav class="navbar navbar-expand">
        <!-- Mobile Sidebar Toggle -->
        <div class="mobile-toggle-icon d-xl-none">
            <i class="bi bi-list"></i>
        </div>

        <!-- Desktop Main Nav -->
        <div class="top-navbar d-none d-xl-block">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <!-- Add more nav links as needed -->
            </ul>
        </div>

        <!-- Mobile Search Toggle -->
        <div class="search-toggle-icon d-xl-none ms-auto">
            <i class="bi bi-search"></i>
        </div>

        <!-- Desktop Searchbar -->
        <form class="searchbar d-none d-xl-flex ms-auto">
            <div class="position-absolute top-50 translate-middle-y search-icon ms-3">
                <i class="bi bi-search"></i>
            </div>
            <input class="form-control" type="text" placeholder="Type here to search">
            <div class="position-absolute top-50 translate-middle-y d-block d-xl-none search-close-icon">
                <i class="bi bi-x-lg"></i>
            </div>
        </form>

        <!-- Right Section (Language, Notifications, User) -->
        <div class="top-navbar-right ms-3">
            <ul class="navbar-nav align-items-center">

                <!-- Language Dropdown -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/img/1x1/us.svg') }}" class="flag-width" alt="flag">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#"><img src="{{ asset('assets/img/1x1/us.svg') }}" class="flag-width me-2" alt="flag">English</a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#"><img src="{{ asset('assets/img/1x1/bd.svg') }}" class="flag-width me-2" alt="flag">বাংলা</a>
                        </li>
                    </ul>
                </li> --}}

                <!-- Notification Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-success"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li>
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Messages</span> <span class="badge bg-primary">9 Unread</span>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-start" href="#">
                                <i class="bi bi-hdd-network fs-5 me-2 text-info"></i>
                                <div>
                                    <div>Server Rebooted</div>
                                    <small class="text-muted">45 min ago</small>
                                </div>
                                <i class="bi bi-x fs-6 ms-auto"></i>
                            </a>
                        </li>
                        <!-- Add more notifications as needed -->
                    </ul>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{-- <img src="{{ auth()->user()->avatar ?? asset('assets/img/profile-30.png') }}" alt="avatar" class="rounded-circle" width="32" height="32"> --}}
                        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : asset('assets/images/avatars/avatar-1.png') }}" class="rounded-circle" width="32" alt="">
                        <span class="ms-2 d-none d-lg-inline">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <div class="dropdown-item-text">
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('settings.index') }}">
                                <i class="bi bi-gear me-2"></i>Settings
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
