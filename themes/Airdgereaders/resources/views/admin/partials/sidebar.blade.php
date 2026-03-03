<aside class="menu-bar menu-sticky">
    <div class="menu-items">
        
        <a href="{{ route('admin.index') }}" class="link"><span class="icon la la-store"></span><span
                class="title">Dashboard</span></a>
       
        
        <a href="{{ route('admin.resources.index') }}" class="link"><span
                class="icon la la-file"></span><span class="title">Resources</span></a>

        <!-- NEW: Simple Admin Pending Resources -->
        <a href="{{ route('admin.simple-pending') }}" class="link"><span
                class="icon la la-clock"></span><span class="title">Pending Approval</span></a>


        <a href="#no-link" class="link" data-target="users" data-toggle="tooltip-menu"
                data-tippy-content="Plans"><span class="icon la la-store"></span><span
                    class="title">Users</span></a>


        <a href="#no-link" class="link" data-target="plans" data-toggle="tooltip-menu"
                data-tippy-content="Plans"><span class="icon la la-store"></span><span
                    class="title">Plans</span></a>
        

        <a href="#" class="link" data-toggle="tooltip-menu" data-target="wallets" data-tippy-content="Wallets"><span
                class="icon la la-laptop"></span><span class="title">Wallet</span></a>



        <a href="{{ route('admin.settings.index') }}" class="link" data-toggle="tooltip-menu" data-tippy-content="Dashboard"><span
                class="icon la la-laptop"></span><span class="title">Settings</span></a>
        

    </div>

    <!-- Applications -->
    <div class="menu-detail" data-menu="plans">
        <div class="menu-detail-wrapper">
            <a href="{{ route('admin.subscriptions.index') }}"><span class="la la-image"></span>Subscriptions</a>
            <a href="{{route('admin.pricings.index')}}"><span class="la la-check-circle"></span>Pricings</a>
            <a href="{{route('admin.plans.index')}}"><span class="la la-comment"></span>Plans</a>
        </div>
    </div>


    <!-- Users -->
    <div class="menu-detail" data-menu="users">
        <div class="menu-detail-wrapper">
             <a href="{{ route('cleanup-users') }}" class="link">
        <span class="icon la la-trash"></span><span class="title">Cleanup</span></a>

            <a href="{{ route('admin.users.index') }}"><span class="la la-image"></span>Users</a>
            <a href="{{route('admin.roles.index')}}"><span class="la la-check-circle"></span>Roles</a>
            <a href="{{route('admin.permissions.index')}}"><span class="la la-comment"></span>Permissions</a>
        </div>
    </div>


    <!-- Transactions -->
    <div class="menu-detail" data-menu="wallets">
        <div class="menu-detail-wrapper">
            <a href="https://projectandmaterials.com/admin/credit"><span class="la la-coins"></span>Credit Management</a>
            <a href="{{ route('admin.wallets.transactions', ['type' => 'withdrawal']) }}"><span class="la la-comment"></span>Withdrawals</a>
            <a href="{{route('admin.wallets.transactions', ['type' => 'earning'])}}"><span class="la la-check-circle"></span> Earnings</a>
           <!--  <a href="{{ route('admin.wallets.holdings') }}"><span class="la la-image"></span>Holdings</a> -->

        </div>
    </div>


   
</aside>
