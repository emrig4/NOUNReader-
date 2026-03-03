<header class="top-bar">

    <!-- Menu Toggler -->
    <button type="button" class="menu-toggler la la-bars" data-toggle="menu"></button>

    <!-- Brand -->
    <span class="brand">Readprojecttopics</span>

    <!-- Search -->
    <form class="hidden md:block ml-10" action="#">
        <label class="form-control-addon-within rounded-full">
            <input type="text" class="form-control border-none" placeholder="Search">
            <span class="flex items-center pr-4">
                <button type="button"
                    class="dark:text-gray-700 dark:hover:text-primary btn btn-link text-gray-300 hover:text-primary text-xl leading-none la la-search"></button>
            </span>
        </label>
    </form>

    <!-- Right -->
    <div class="flex items-center ml-auto">

        <!-- Dark Mode -->
        <label class="switch switch_outlined" data-toggle="tooltip" data-tippy-content="Toggle Dark Mode">
            <input id="darkModeToggler" type="checkbox">
            <span></span>
        </label>

        <!-- Fullscreen -->
        <button type="button"
            class="hidden lg:inline-block btn-link ml-3 px-2 text-2xl leading-none la la-expand-arrows-alt"
            data-toggle="tooltip" data-tippy-content="Fullscreen" id="fullScreenToggler"></button>

    </div>
</header>
