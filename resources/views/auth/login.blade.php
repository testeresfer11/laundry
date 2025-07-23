
<!doctype html>
<html lang="en">
  <head>
  	<title>User Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="{{asset('user/auth/css/style.css')}}">
    <style>.img {
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
    }
	.error{
		color: red;
	}
        </style>
	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-10">
					<div class="wrap d-md-flex">
						<img src="{{url('user/auth/images/bg-2.png')}}" class = "img">
						
						<div class="login-wrap p-4 p-md-5">
							 
							<div class="d-flex">
								<div class="w-100">
									<h3 class="mb-4">Sign In</h3>
								</div>
								<div class="w-100">
									<p class="social-media d-flex justify-content-end">
										<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
										<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
									</p>
								</div>
							</div>
					
					<form action="{{route('user.login')}}" class="signin-form" id="loginForm" method="post">
						@csrf
						<div class="form-group mb-3">
							<label class="label" for="name">Email</label>
							<input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" required>
							@error('email')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror

						</div>
						<div class="form-group mb-3">
							<label class="label" for="password">Password</label>
							<input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required>
							@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>
						<div class="form-group mt-4">
							<button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign In</button>
						</div>
					</form>
		            <div class="form-group d-md-flex">
		            	<div class="w-50 text-left">
			            	<label class="checkbox-wrap checkbox-primary mb-0">Remember Me
									<input type="checkbox" checked>
									<span class="checkmark"></span>
							</label>
						</div>
						<div class="w-50 text-md-right">
							<a href="{{route('user.forget-password')}}">Forgot Password</a>
						</div>
		            </div>
		          <p class="text-center">Not a member? <a data-toggle="tab" href="#signup">Sign Up</a></p>
		        </div>
		      </div>
				</div>
			</div>
		</div>
	</section>

  <script src="{{asset('user/auth/js/jquery.min.js')}}"></script>
  <script src="{{asset('user/auth/js/popper.js')}}"></script>
  <script src="{{asset('user/auth/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('user/auth/js/main.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
  	<script>
	  $(document).ready(function () {
		$('#loginForm').validate({
		  rules: {
			email: {
			  required: true,
			  email: true
			},
			password: {
			  required: true,
			  minlength: 8
			},
		  },
		  messages: {
			email: {
			  required: 'Please enter Email Address.',
			  email: 'Please enter a valid Email Address.',
			},
			password: {
			  required: 'Please enter Password.',
			  minlength: 'Password must be at least 8 characters long.',
			},
		  },
		  submitHandler: function (form) {
			form.submit();
		  }
		});
	  });
	</script>
	</body>
</html>

