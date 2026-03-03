<!-- PROFESSIONAL USER ACCOUNT NAVIGATION -->
<div class="col-md-12">
    <div id="style-4-scrollbar" class="ereaders-shop-filter md:py-5 px-0 overflow-x-scroll">
        <!-- Nav tabs -->
        <ul style="display: flex; flex-wrap: wrap; gap: 1rem;" class="nav-tabs pull-left" role="tablist">
            
            <!-- Account Actions -->
            <li role="presentation" class="{{ request()->routeIs('account.index') ? 'active' : '' }}">
                <a href="{{ route('account.index') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li role="presentation" class="{{ request()->routeIs('account.subscription') ? 'active' : '' }}">
                <a href="{{ route('account.subscription') }}">
                    <i class="fas fa-wallet"></i>
                    Wallet
                </a>
            </li>
           
            <li role="presentation" class="{{ request()->routeIs('account.favorites') ? 'active' : '' }}">
                <a href="{{ route('account.favorites') }}">
                    <i class="fas fa-heart"></i>
                    Favorites
                </a>
            </li>
            
            <!-- Management Actions -->
            <li role="presentation" class="{{ request()->routeIs('account.followings') ? 'active' : '' }}">
                <a href="{{ route('account.followings') }}">
                    <i class="fas fa-user-friends"></i>
                    Followings
                </a>
            </li>
            <li role="presentation" class="{{ request()->routeIs('account.notifications') ? 'active' : '' }}">
                <a href="{{ route('account.notifications') }}">
                    <i class="fas fa-bell"></i>
                    Notifications
                </a>
            </li>
            <li role="presentation" class="{{ request()->routeIs('account.settings') ? 'active' : '' }}">
                <a href="{{ route('account.settings') }}">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </li>
            
            <!-- Logout Action -->
           <li role="presentation">
    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); this.closest('form').submit();"
           style="display: block; padding: 10px; text-decoration: none; cursor: pointer;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </form>
</li>

            </li>

        </ul>
        
    </div>
</div>

<!-- Add this to your layout head section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">