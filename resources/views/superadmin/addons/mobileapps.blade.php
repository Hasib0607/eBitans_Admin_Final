@extends('admin.layouts.main')
@section('content')
	{{-- styles --}}
	@include('superadmin.addons.share.style')
	
	<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
		{{-- addons navbar --}}
		@include('superadmin.addons.share.addons-nav', ['mobileapps'=>true])
		
		<div class="container-fluid mt-4" id="toplist">
			{{--mobile app header --}}
			<div class="row">
				<div class="col-md-6">
					<h4>All Mobile Apps</h4>
				</div>
				<div class="col-md-6">
					<ul>
						<!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
						<!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
					</ul>
				</div>
			</div>
			{{--mobile app list main card--}}
			<div class="row mt-5 productlist">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							{{--success alert--}}
							@if (Session::has('success_message'))
								<div class="alert alert-success">{{Session::get('success_message')}}</div>
							@endif
							<div class="table-responsive">
								{{--mobile app list table--}}
								<table class="table" id="taskfilterresult" width="100%">
									<thead>
									<tr>
										<th width="4%"><input type="checkbox"></th>
										<th width="5%">Apps Name</th>
										<th width="20%">Logo</th>
										<th width="10%">Customer Name</th>
										<th width="5%">Customer Phone</th>
										<th width="10%">Expiry Date</th>
										<th width="10%">Status</th>
										<th width="10%">Action</th>
									</tr>
									</thead>
									<tbody>
									@if(isset($data) && count($data)>0)
										{{--table body rows--}}
										@foreach($data as $key=>$dm)
											<tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
												{{--mobile app link modal--}}
												<div class="modal fade" id="exampleModal{{$key}}" tabindex="-1"
												     aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog">
														<form method="post" action="{{route('saveappslink')}}">
															@csrf
															<div class="modal-content">
																{{--mobile app link modal header--}}
																<div class="modal-header">
																	<h5 class="modal-title" id="exampleModalLabel">Apps
																		Link</h5>
																	<button type="button" class="btn-close"
																	        data-bs-dismiss="modal"
																	        aria-label="Close"></button>
																</div>
																{{--mobile app link modal body and input fields--}}
																<div class="modal-body">
																	<div class="form-group py-3">
																		<label for="exampleInputEmail1">Link</label>
																		<input type="text" class="form-control"
																		       id="exampleInputEmail1" name="link"
																		       value="{{$dm->url ?? ""}}"
																		       aria-describedby="emailHelp"
																		       placeholder="Enter Link">
																	</div>
																	<input type="hidden" name="appid"
																	       value="{{$dm->id ?? ''}}">
																
																</div>
																{{--mobile app link modal footer and buttons--}}
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary"
																	        data-bs-dismiss="modal">Close
																	</button>
																	<button type="submit" class="btn btn-primary">Save
																		changes
																	</button>
																</div>
															</div>
														</form>
													</div>
												</div>
												{{--get store, customer, user data--}}
                          <?php
                          $store = DB::table('stores')->where('id', $dm->store_id)->first();
                          $customer = DB::table('customers')->where('id', $store->customer_id ?? '')->first();
                          $user = DB::table('users')->where('id', $customer->uid ?? '')->first();
                          ?>
												{{--mobile app table row--}}
												<td><input type="checkbox" name="id" value="{{$dm->id ?? ''}}"></td>
												<td>
													{{$dm->name ?? ""}}
												</td>
												<td>
													<img src="{{URL::to('/')}}/assets/images/category/{{$dm->image}}"
													     width="100px">
												</td>
												<td>{{$user->name ?? ""}}</td>
												<td>{{$user->phone ?? ""}}</td>
												<td>{{date('d-m-Y', strtotime($dm->expiry_date))}}</td>
												<td>{{$dm->status}}</td>
												<td>
													<a @if($dm->status=='Request Send') href="{{URL::to('/')}}/mobileapps/{{$dm->id}}/Payment Verified"
													   @elseif($dm->status=='Payment Verified') href="{{URL::to('/')}}/mobileapps/{{$dm->id}}/Processing"
													   @elseif($dm->status=='Processing') href="{{URL::to('/')}}/mobileapps/{{$dm->id}}/Download"
													   @elseif($dm->status=='Download') href="javascript:void"
													   @else href="javascript:void(0)"
													   @endif class="btn btn-primary">@if($dm->status=='Request Send')
															Payment Verified
														@elseif($dm->status=='Payment Verified')
															Processing
														@elseif($dm->status=='Processing')
															Download
														@elseif($dm->status=='Download')
															Complete
														@else
															Pending
														@endif</a>
													<a href="javascript:void(0)" class="btn btn-info"
													   data-bs-toggle="modal" data-bs-target="#exampleModal{{$key}}">Link</a>
												</td>
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
	{{--Will Remove Future--}}
	<script>
    // $(document).ready(function () {
    //   $("#taskfilter").on("keyup", function () {
    //     var value = $(this).val().toLowerCase();
    //     $("#taskfilterresult tbody tr").filter(function () {
    //       $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    //     });
    //   });
    //
    // });
    
    // function exportTasks(_this) {
    //   let _url = $(_this).data('href');
    //   window.location.href = _url;
    // }
	</script>
@endpush
