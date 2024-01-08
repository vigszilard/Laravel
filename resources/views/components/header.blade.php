<div id="header-container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" height="50" alt="Logo">
        </a>

        <div class="ml-auto d-flex align-items-center">
            @guest
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link d-none d-md-inline-block" href="{{ route('login') }}">
                                <button type="button" class="btn btn-primary">
                                    <i class="fas fa-user"></i>
                                    Log in
                                </button>
                            </a>
                            <a class="nav-link d-inline-block d-md-none" href="{{ route('login') }}">
                                <i class="fas fa-user"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-none d-md-inline-block" href="{{ route('register') }}">
                                <button type="button" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i>
                                    Register
                                </button>
                            </a>
                            <a class="nav-link d-inline-block d-md-none" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                <div class="ml-auto d-flex align-items-center">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}">
                                    Articles
                                </a>
                            </li>
                            @if (auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
                                <!-- Writer or Editor -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dashboard') }}">
                                        Dashboard
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Log out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endguest
        </div>
    </nav>
</div>
