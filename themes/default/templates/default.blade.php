@section('content')
    {{--*/ $block = '%block%' /*--}}
    @if (!$block->getIsParsed())
        {{ $block->getBodyForInsertion() }}
    @endif
    @parent
@endsection