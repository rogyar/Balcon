@section('content')
    {{--*/ $block = '%block%' /*--}}
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    @foreach ($page->getListOfPosts() as $blogpost)
                        <h3>{{ $page->getPostInfo($blogpost)['title']}}</h3>
                        {{ $page->getExcerpt($blogpost) }}
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @parent
@endsection