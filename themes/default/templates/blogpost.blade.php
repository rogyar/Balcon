@section('content')
    {{--*/ $block = '%block%' /*--}}
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2>{{ $page->getParam('title')}}}</h2>
                    <div class="info">
                        <div class="post-date">2000-00-00</div>
                        <div class="post-author">supermen</div>
                    </div>
                    <div class="post-body">
                        {{ $page->getExcerpt($blogpost) }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @parent
@endsection