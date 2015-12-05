@section('content')
    {{--*/ $block = '%block%' /*--}}
    @if (!$block->getIsParsed())
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        @foreach ($page->getListOfPosts(0, 4) as $blogpost)
                            <h3>{{ $page->getPostInfo($blogpost)['title']}}</h3>
                            {{ $page->getExcerpt($blogpost) }}
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    @parent
@endsection