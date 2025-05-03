<!DOCTYPE html>
<html lang="ja">
<x-layouts.head/>

<body>
    <x-layouts.load/>
    @yield('content')
    @stack('scripts')
</body>

<x-layouts.footer/>
</html>
