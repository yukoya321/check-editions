@include('layouts.header')
<div class="container bg-light text-center mt-5 pb-3">
  <h1 class="p-3">@yield('page-title', 'Home')</h1>
  @yield('content')
</div>
@include('layouts.footer')