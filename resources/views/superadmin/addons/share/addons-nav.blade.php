<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
	<div class="row">
		<div class="col-md-12">
			<nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
				<ol class="breadcrumb" style="background-color:transparent;color:#fff">
					<li class="breadcrumb-item @if(isset($mobileapps)) active @endif">
						<a href="{{route('superadmin.mobilapps')}}">
							<img src="{{URL::to('/')}}/img/cubes.png"> <br> Mobileapps
						</a>
					</li>
					<li class="breadcrumb-item @if(isset($websitesetup)) active @endif">
						<a href="{{route('superadmin.websitesetup')}}">
							<img src="{{URL::to('/')}}/img/cubes.png"> <br> Website Setup
						</a>
					</li>
					<li class="breadcrumb-item @if(isset($paymentgateway)) active  @endif">
						<a href="{{route('superadmin.paymentgateway')}}">
							<img src="{{URL::to('/')}}/img/cubes.png"> <br> Payment Gateway
						</a>
					</li>
					
					<li class="breadcrumb-item @if(isset($addons)) active @endif">
						<a href="{{route('superadmin.addons.add')}}">
							<img src="{{URL::to('/')}}/img/cubes.png"> <br> Add Addons
						</a>
					</li>
					
					<li class="breadcrumb-item @if(isset($modulus)) active @endif">
						<a href="{{route('superadmin.modulus.add')}}">
							<img src="{{URL::to('/')}}/img/cubes.png"> <br> Add Modulus
						</a>
					</li>
				
				</ol>
			</nav>
		</div>
	</div>
</div>
