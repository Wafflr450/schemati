@php
 $links = [
     //[
     // 'name' => 'UI',
     // 'href' => '/ui',
     // 'current' => true,
     //],
     [
         'name' =>
             'Schematics',
         'href' =>
             '/schematics',
         'current' => false,
     ],
 ];

 if (Auth::check()) {
     $player = Auth::user()
         ->player;
     $links[] = [
         'name' =>
             'Upload Schematic',
         'href' =>
             '/schematics/upload/new',
         'current' => false,
     ];

     if (
         $player->is_tag_admin
     ) {
         $links[] = [
             'name' =>
                 'Tag Editor',
             'href' =>
                 '/tag-editor',
             'current' => false,
         ];
     }
 }
@endphp

<nav
     class="shadow-[inset 0px 0px 0px 2px] border-b border-neutral-950 px-2 py-2.5 shadow-pink-500 backdrop-blur-xl sm:px-4">
 <div
      class="flex flex-wrap items-center justify-between">
  <a href="{{ route('index') }}"
     class="flex items-center">
   <x-animated-logo-svg
                        class="flex w-8 items-center justify-center" />
  </a>
  <div class="flex md:order-2">
   @auth
    <div
         class="flex items-center space-x-3 rtl:space-x-reverse md:order-2 md:space-x-0">
     <button type="button"
             class="flex rounded-full text-sm focus:ring-4 focus:ring-gray-300 md:me-0"
             id="user-menu-button"
             aria-expanded="false"
             data-dropdown-toggle="user-dropdown"
             data-dropdown-placement="bottom">
      <span
            class="sr-only">Open
       user menu</span>
      <img class="h-8 w-8 rounded-full"
           src="{{ Auth::user()->profile_photo_url }}"
           alt="User avatar" />
     </button>
     <!-- Dropdown menu -->
     <div class="z-50 my-4 hidden list-none divide-y divide-gray-100 rounded-lg bg-neutral text-base shadow"
          id="user-dropdown">
      <div class="px-4 py-3">
       <span
             class="block text-sm">
        {{ Auth::user()->name }}
       </span>
      </div>
      <ul class="py-2"
          aria-labelledby="user-menu-button">
       <li>
        <form method="POST"
              action="{{ route('logout') }}"
              x-data>
         @csrf

         <x-dropdown-link href="{{ route('logout') }}"
                          @click.prevent="$root.submit();">
          {{ __('Log Out') }}
         </x-dropdown-link>
        </form>
       </li>
      </ul>
     </div>
     <button data-collapse-toggle="navbar-user"
             type="button"
             class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:focus:ring-gray-600 md:hidden"
             aria-controls="navbar-user"
             aria-expanded="false">
      <span
            class="sr-only">Open
       main menu</span>
      <svg class="h-5 w-5"
           aria-hidden="true"
           xmlns="http://www.w3.org/2000/svg"
           fill="none"
           viewBox="0 0 17 14">
       <path stroke="currentColor"
             stroke-linecap="round"
             stroke-linejoin="round"
             stroke-width="2"
             d="M1 1h15M1 7h15M1 13h15" />
      </svg>
     </button>
    </div>
   @else
    {{-- <a href="{{ route('login') }}"
            class="text-sm text-gray-700 rounded-lg md:bg-transparent md:text-blue-700 md:p-2 dark:text-white">{{ __('Log in') }}</a> --}}
    <a x-data
       x-on:click="$dispatch('login-modal')"
       class="mr-2 block cursor-pointer rounded py-2 pl-3 pr-4 text-gray-700 text-white hover:text-pink-500 md:p-0">
     {{-- <div class="hidden mr-2 sm:inline-block">
                            <i class="fas fa-sign-in-alt "></i>
                        </div>  --}}
     Login
    </a>
    {{-- <a href="{{ route('register') }}"
            class="text-sm text-gray-700 rounded-lg md:bg-transparent md:text-blue-700 md:p-2 dark:text-white">{{ __('Register') }}</a> --}}
    <a x-data
       x-on:click="$dispatch('register-modal')"
       class="block cursor-pointer rounded py-2 pl-3 pr-4 text-gray-700 text-white hover:text-pink-500 md:p-0">
     {{-- <div class="hidden mr-2 sm:inline-block">
                            <i class="fas fa-user-plus "></i>
                        </div>  --}}
     Register
    </a>
   @endauth
   <button data-collapse-toggle="mobile-menu"
           type="button"
           class="inline-flex items-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 md:hidden"
           aria-controls="mobile-menu"
           aria-expanded="false">
    <span class="sr-only">Open
     main menu</span>
    <svg class="h-6 w-6"
         fill="none"
         stroke="currentColor"
         viewBox="0 0 24 24"
         xmlns="http://www.w3.org/2000/svg">
     <path stroke-linecap="round"
           stroke-linejoin="round"
           stroke-width="2"
           d="M4 6h16M4 12h16m-7 6h7">
     </path>
    </svg>
   </button>
  </div>
  <div class="hidden w-full md:block md:w-auto"
       id="mobile-menu">
   <ul
       class="mt-4 flex flex-col md:mt-0 md:flex-row md:space-x-8 md:text-sm md:font-medium">
    @foreach ($links as $link)
     <li>
      <a href="{{ $link['href'] }}"
         class="block rounded py-2 pl-3 pr-4 text-gray-700 text-white hover:text-pink-500 md:p-0"
         aria-current="{{ $link['current'] ? 'page' : '' }}">{{ $link['name'] }}</a>
     </li>
    @endforeach
   </ul>
  </div>
 </div>
</nav>
