<!doctype html>
<html>
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="_token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link href={{ asset('/css/bootstrap-table.css') }} rel="stylesheet" type="text/css">
	<link href={{ asset('/css/site.css') }} rel="stylesheet" type="text/css">
	<script src={{ asset('js/jquery-2.1.4.min.js') }}></script>
	<link rel="shortcut icon" type="image/x-icon" href="../icon/icon_web.png"/>
	
	<!-- Latest compiled and minified JavaScript -->
	<script src={{ asset('js/bootstrap-table.min.js') }}></script>
	
	@yield('resource')

</head>
<body>

	@yield('content')
	
	<div id="alertBox" class="alert-box"></div>

</body>
</html>


@yield('script')

<script type="text/javascript">
	
	function toastAlert(Msg, status) {
		if (status === 1) {
			$('#alertBox').removeClass('wrong-input').addClass('success-toast');
		}
		else {
			$('#alertBox').removeClass('success-toast').addClass('wrong-input');
		}
		$('#alertBox').text(Msg).fadeIn();
		
		setTimeout(function () {
			$('#alertBox').fadeOut();
		}, 2000);
	}
	
</script>


