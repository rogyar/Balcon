@section('content')
    {{--*/ $block = '%block%' /*--}}
    @if (!$block->getIsParsed())
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        @foreach ($page->getListOfPosts(0, 4) as $blogpost)
                            <h3>
                                <a href="{{ $page->getPostInfo($blogpost)['url']}}">
                                    {{ $page->getPostInfo($blogpost)['title']}}
                                </a>
                            </h3>
                            {!! html_entity_decode($page->getExcerpt($blogpost)) !!}
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    @parent
@endsection