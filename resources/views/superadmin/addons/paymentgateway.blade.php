@extends('admin.layouts.main')
@section('content')
	{{-- styles --}}
	@include('superadmin.addons.share.style')
	
	<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
		{{-- addons navbar --}}
		@include('superadmin.addons.share.addons-nav', ['paymentgateway'=>true])
		
		<div class="container-fluid mt-4" id="toplist">
			{{--paymentgeteway header--}}
			<div class="row">
				<div class="col-md-6">
					<h4>Payment Gateway</h4>
				</div>
				<div class="col-md-6">
					<ul>
						<!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
						<!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
					</ul>
				</div>
			</div>
			{{--payment gateway main cards--}}
			<div class="row mt-5 productlist">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							{{--payment gateway success message--}}
							@if (Session::has('success_message'))
								<div class="alert alert-success">{{Session::get('success_message')}}</div>
							@endif
							{{--payment gateway table--}}
							<div class="table-responsive">
								<table class="table" id="taskfilterresult" width="100%">
									<thead>
									<tr>
										<th width="4%"><input type="checkbox"></th>
										<th width="5%">Store Name</th>
										<th width="10%">Payment Company</th>
										<th width="10%">SSL Store Id</th>
										<th width="10%">SSL Store Password</th>
										<th width="10%">Bkash App Key</th>
										<th width="10%">Bkash App Secret</th>
										<th width="10%">Bkash Api Username</th>
										<th width="10%">Bkash Api Password</th>
										<th width="10%">Date</th>
									</tr>
									</thead>
									<tbody>
									@if(isset($data) && count($data)>0)
										{{--payment gateway rows--}}
										@foreach($data as $key=>$dm)
											<tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
												<td><input type="checkbox" name="id" value="{{$dm->id}}"></td>
												<td>
													{{$dm->store_id ?? ""}}
												</td>
												<td>{{$dm->payment_company}}</td>
												<td>{{$dm->ssl_store_id}}</td>
												<td>{{$dm->ssl_store_password}}</td>
												<td>{{$dm->app_key}}</td>
												<td>{{$dm->app_secret}}</td>
												<td>{{$dm->api_username}}</td>
												<td>{{$dm->api_password}}</td>
												<td>{{$dm->created_at}}</td>
											</tr>
										@endforeach
									@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
@push('scripts')
	<script>
		{{--will work future--}}
    /*$(document).ready(function () {
      $("#taskfilter").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#taskfilterresult tbody tr").filter(function () {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
      
    });
    
    function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
    }*/
	</script>
@endpush
