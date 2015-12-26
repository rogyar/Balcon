@section('content')
    {{--*/ $block = '%block%' /*--}}
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    @if (!$block->getIsParsed())
                        {!! html_entity_decode($block->getBodyForInsertion()) !!}
                    @endif
                </div>
            </div>
        </div>
    </section>
    @parent
@endsection