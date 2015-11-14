@section('content')
    {{--*/ $var = 'test' /*--}}
    <p>Hello World {{ $var }}</p>
    @parent
@endsection

@section('content')
    {{--*/ $var = 'test again' /*--}}
    <p>Hello World Again {{ $var  }}</p>
    @parent
@endsection

@section('content')
    {{--*/ $var = 'test here' /*--}}
    <p>Hello World Again and Again {{ $somevar }}</p>
    @parent
@endsection

@yield('content')