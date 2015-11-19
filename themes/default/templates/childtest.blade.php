@section('content')
    {{--*/ $block = '%block%' /*--}}
    @if (!$block->getIsParsed())
        {{ $block->getBodyForInsertion() }}
    @endif
    {{ $block->getChild('homefooter')->getBodyForInsertion() }}
    @parent
@endsection
