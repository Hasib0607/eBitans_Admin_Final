<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <title>Pos</title>
    <!-- <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/08981fce2e.js" crossorigin="anonymous"></script>
    <script>
        window.Laravel = {
            appUrl: '{{ env('APP_URL') }}',
        };
    </script>
</head>

<body style="background-color:#FFE8EE">
<div id="app">
    <my-component></my-component>
</div>
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
<script>

    localStorage.setItem('bid',<?php echo $branch_id; ?>);
    window.onscroll = function () {
        myFunction()
    };

    var header = document.getElementById("topheader");
    var header1 = document.getElementById("imgnone");
    var header2 = document.getElementById("mt3");
    var sticky = header.offsetTop;
    var sticky = header1.offsetTop;
    var sticky2 = header2.offsetTop;

    function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("fixed");
            header1.classList.add("d-none");
            header2.classList.add("mt-3");
        } else {
            header.classList.remove("fixed");
            header2.classList.remove("mt-3");
            header1.classList.remove("d-none");
        }
    }
</script>
</body>
</html>
