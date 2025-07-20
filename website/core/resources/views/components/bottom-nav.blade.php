<nav class="mobile-bottom-nav d-md-none">
    <ul class="d-flex justify-content-around m-0 list-unstyled">
        <li>
            <a href="{{ route('home') }}" class="nav-link text-center">
                <i class="las la-home fs-3"></i>
                <span class="fs-12 d-block">@lang('Home')</span>
            </a>
        </li>
        <li>
            <a href="{{ route('live.tv') }}" class="nav-link text-center">
                <i class="las la-th-large fs-3"></i>
                <span class="fs-12 d-block">@lang('Categories')</span>
            </a>
        </li>
        <li>
            <a href="{{ route('search') }}" class="nav-link text-center">
                <i class="las la-search fs-3"></i>
                <span class="fs-12 d-block">@lang('Search')</span>
            </a>
        </li>
        <li>
            @auth
                <a href="{{ route('user.profile.setting') }}" class="nav-link text-center">
                    <i class="las la-user fs-3"></i>
                    <span class="fs-12 d-block">@lang('Profile')</span>
                </a>
            @else
                <a href="{{ route('user.login') }}" class="nav-link text-center">
                    <i class="las la-user fs-3"></i>
                    <span class="fs-12 d-block">@lang('Login')</span>
                </a>
            @endauth
        </li>
    </ul>
</nav>