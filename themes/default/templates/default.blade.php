@section('content')
    {{--*/ $block = '%block%' /*--}}
    @if (!$block->isParsed())
        {{ $block->getBodyForInsertion() }}
    @endif
    @parent
@show
