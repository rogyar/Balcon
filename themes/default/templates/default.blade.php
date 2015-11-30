@section('content')
    {{--*/ $block = '%block%' /*--}}
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    @if (!$block->getIsParsed())
                        {{ $block->getBodyForInsertion() }}
                    @endif
                </div>
            </div>
        </div>
    </section>
    @parent
@endsection