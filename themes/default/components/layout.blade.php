<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ isset($pageParams['title'])? $pageParams['title'] : '' }}</title>
    <meta name="description"
          content="{{ isset($pageParams['metaDescription']) ? $pageParams['metaDescription'] : '' }}">
    
    @section('stylesheets')
        <link rel="stylesheet" href="css/styles.css?v=1.0">
        @show
 <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
</head>

<body>
@section('content')
    @parent
@endsection
@yield('content')
<footer>
    @section('javascripts')
    @show
    &copy; :]
</footer>
</body>
</html>


