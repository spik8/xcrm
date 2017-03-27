<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials._head')
    @include('partials._style')
    @yield('style')
</head>

<body>

    @include('partials._nav')

    <div class="container">

        @yield('content')

        @include('partials._footer')

    </div> <!-- end of .container -->

        @include('partials._javascript')
        @yield('script')
</body>

</html>