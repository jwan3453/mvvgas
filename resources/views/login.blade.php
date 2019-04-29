@extends('template.layout')

@section('content')
	
	<div class="site-header">
		<div class="site-logo"></div>
	</div>

	<div class="login-panel">
		<label class="login-label">Enter Pin</label>
		<input class="pin-input" id="pinInput"   type="password" placeholder="please enter pin"/>
		<div class="login-btn" id="loginBtn">Log in</div>
	</div>
@stop

@section('script')
	<script type="text/javascript">
		$(document).ready(function (fn) {
			
			//checkout localstorage login info
			var tokenObj = localStorage.getItem('token');
			var token = '';
			if(tokenObj) {
				token = JSON.parse(tokenObj).token
			}
			
			if(token) {
				window.location.href = "/adminoverview";
			}
			
			
			function saveToLocalStorage(data){
				localStorage.setItem( "token", JSON.stringify({
					token: data.api_token,
					role: data.role,
				}));
			}
			
			
			$('#loginBtn').click(function() {
				$.ajax({
					type: 'POST',
					async: false,
					url: ' /api/login',
					dataType: 'json',
					data: {
						'storePin': $('#pinInput').val(),
					},
					headers: {
						'Accept': 'application/json',
					
					},
					success: function (data) {
						if(data.response && data.response.api_token) {
							saveToLocalStorage(data.response);
							if(data.response.role === 1) {
								window.location.href = "/adminoverview";
							} else {
								toastAlert('Fail to login in, please check you pin code', 2);
							}
						} else {
							toastAlert('Fail to login in, please check you pin code', 2);
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						alert(errorThrown)
					}
				});
			})
		})
	</script>
@stop