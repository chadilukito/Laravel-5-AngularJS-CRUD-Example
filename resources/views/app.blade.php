<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Book List</title>
    <link href="{{ asset('css/fontawesome.5.6.3.all.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

	<script src="{{ asset('js/lib/jquery-3.3.1.slim.min.js') }}"></script>
    <script src="{{ asset('js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('js/lib/underscore-min.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/lib/angular/angular.min.js') }}"></script>  
	<script src="{{ asset('js/lib/angular/angular-route.min.js') }}"></script>
    <script src="{{ asset('js/lib/angular/angular-file-saver.bundle.min.js') }}"></script>
    <script src="{{ asset('js/lib/angular/angular-growl-notifications.min.js') }}"></script>
    <script src="{{ asset('js/lib/angular/restangular.min.js') }}"></script>
	<script src="{{ asset('js/lib/angular/dirPagination.js') }}"></script>
	<script src="{{ asset('js/mainaq.js') }}"></script>
	<script src="{{ asset('js/controllers/BooklistController.js') }}"></script>
    
    <style>
        growl-notifications {
            position: fixed;
            top: 100px;
            right: 10px;
            z-index: 1000;
        }

        growl-notifications growl-notification {
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 15px 30px;
            width: 200px;
            display: block;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body ng-app="mainApp">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">BookList</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="/">Home</a></li>
					<!--<li><a href="/books">BookList</a></li>-->
				</ul>
			</div>
		</div>
	</nav>
    <growl-notifications></growl-notifications>
	<div class="container">
		<ng-view></ng-view>
	</div>
</body>
</html>