@extends('admin.layouts.main')
@section('content')
<style>
    .input-group-text{
        
    }
    #message {
  display:block;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#message p {
  padding: 0px 30px;
  margin-bottom:0px;
  font-size: 13px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}
</style>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{route('admin.createwebemail')}}" method="post">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Email Information</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="basic-url" class="form-label">Email</label>
            <div class="input-group mb-3">
            <input type="text" style="width:100%;font-size: 0.875rem;font-weight: 400;line-height: 1.5rem;appearance: none;border-radius: 0.375rem;transition: 0.2s ease;border: 1px solid #d2d6da !important;padding: 0.5rem 8px !important;" name="email" placeholder="Email's username" aria-label="Recipient's username" aria-describedby="basic-addon2" autocomplete="off">
            <span class="input-group-text" id="basic-addon2" style="background-color: #8b878787;height: 41px;padding: 0px 19px;">{{'@'.$domain}}</span>
            </div>

            <label for="basic-url" class="form-label">Password</label>
            <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" id="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" title="Must contain at least one number and one special character and one uppercase and lowercase letter, and at least 8 or more characters" aria-describedby="basic-addon3">
            </div>
            <div id="message">
              <p>Password must contain the following:</p>
              <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
              <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
              <p id="number" class="invalid">A <b>number</b></p>
              <p id="special" class="invalid">A <b>Special Character</b></p>
              <p id="length" class="invalid">Minimum <b>8 characters</b></p>
            </div>
          </div> 
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{route('admin.changewebmailpassword')}}" method="post">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="basic-url" class="form-label">Email</label>
            <div class="input-group mb-3">
            <input type="text" class="form-control" name="email" placeholder="Email's username" aria-label="Recipient's username" aria-describedby="basic-addon2" autocomplete="off" id="recipient-name" readonly>
            </div>

            <label for="psw" class="form-label">Password</label>
            <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" id="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" title="Must contain at least one number and one special character and one uppercase and lowercase letter, and at least 8 or more characters" aria-describedby="basic-addon3">
            </div>
            <div id="message">
              <p>Password must contain the following:</p>
              <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
              <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
              <p id="number" class="invalid">A <b>number</b></p>
              <p id="special" class="invalid">A <b>Special Character</b></p>
              <p id="length" class="invalid">Minimum <b>8 characters</b></p>
            </div>
          </div> 
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.emaillist')}}">
                            <img src="{{URL::to('/')}}/img/icons/rating.png"> <br> <span class="nav-link-text ms-1">Email</span>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4>All Email </h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="display:block;border-radius:0px !important">Create Email</a></li>
                <li style="padding:0px;border:0px;"><a data-href="/customerexport" onclick="exportTasks(event.target);" style="display:block;border-radius:0px !important" class="btn btn-secondary"> Excel </a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2" style="padding-right:1px;">
                        <form id="submitform" method="post" action="">
                        @csrf
                        <input type="hidden" name="text2" id="selectids">
                        <select class='form-control' name="action" id="action">
                            <option value="select">@if(Session::has('lang') && Session::get('lang')=='bn') সিলেক্ট  অপসন @else Select Option @endif</option>
                            <option value="delete">@if(Session::has('lang') && Session::get('lang')=='bn') ডিলিট @else Delete @endif</option>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding-left:0px;">
                        <p id="submit" class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn') আবেদন  @else Apply @endif</p>
                        </form>
                    </div>
                    <div class="col-md-7"></div>
                    <div class="col-md-2">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive" id="desktoptable">
                    <table class="table table-striped" id="taskfilterresult" width="100%">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th width="10%">Email</th>
                                <th width="10%"> Login </th>
                                <th width="10%"> Change Password </th>
                                <th width="16%"> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $customer)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td>
                                    <input type="checkbox" name="selectedid" value="{{$customer->email}}" id="id" class="checkSingle">
                                </td>
                                <td>
                                    {{$customer->name}}
                                </td>
                                <td>
                                    <a href="{{$weburl}}" class="btn btn-info" target="_blank"> Login</a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal1" class="btn btn-info" data-bs-whatever="{{$customer->name}}"> Change Password</a>
                                </td>
                                <td>
                                    <a href="{{URL::to('/')}}/webemails/delete/{{$customer->name}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
                                </td>
                            </tr>
                            @endforeach
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
var myInput = document.getElementById("psw");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var special = document.getElementById("special");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box


// When the user starts to type something inside the password field
$("#psw").on('keyup',function(){
    var val=$("#psw").val();
    var lowerCaseLetters = /[a-z]/g;
  if(val.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(val.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(val.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  var specialss = /[!@#$%^&*]/g;
  if(val.match(specialss)) {  
    special.classList.remove("invalid");
    special.classList.add("valid");
  } else {
    special.classList.remove("valid");
    special.classList.add("invalid");
  }
  
  // Validate length
  if(val.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
})
</script>
<script>
const exampleModal = document.getElementById('exampleModal1')
exampleModal.addEventListener('show.bs.modal', event => {
  // Button that triggered the modal
  const button = event.relatedTarget
  // Extract info from data-bs-* attributes
  const recipient = button.getAttribute('data-bs-whatever')
  // If necessary, you could initiate an AJAX request here
  // and then do the updating in a callback.
  //
  // Update the modal's content.
  const modalTitle = exampleModal.querySelector('.modal-title')
  const modalBodyInput = exampleModal.querySelector('.modal-body input')

  modalTitle.textContent = `New message to ${recipient}`
  modalBodyInput.value = recipient
})
 $('#submit').on('click',function(){
     var form = $(this).parents('form');
     var note=$('#action').val();
     if(note != 'select'){
        swal.fire({
          title: 'Are you sure?',
          text: "You want to "+note+" this selected item",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, '+note+' it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
              console.log(form);
            $('#submitform').submit();
            form.submit();
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ){
            swal.fire(
              'Cancelled',
              ''+note+' Cancel :)',
              'error'
            )
          }
        })
     }
 })
$(document).ready(function() {
    $("#checkedAll").change(function() {
        debugger;
        if (this.checked) {
            $(".checkSingle").each(function() {
                debugger;
                this.checked=true;
                var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
        } else {
            $(".checkSingle").each(function() {
                this.checked=false;
            });
            var valuesArray ='';
            $("#selectids").val(valuesArray);
            $("#selectdelids").val(valuesArray);
        }
    });
    $(".checkSingle").click(function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".checkSingle").each(function() {
                if (!this.checked)
                    isAllChecked = 1;
                var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
            if (isAllChecked == 0) {
                $("#checkedAll").prop("checked", true);
            }     
        }
        else {
            $("#checkedAll").prop("checked", false);
            var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
        }
    });
});
    $(document).ready(function(){
      $("#taskfilter").on("keyup", function() {
          debugger;
        var value = $(this).val().toLowerCase();
        debugger;
        $("#taskfilterresult tbody tr").filter(function() {
            debugger;
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          debugger;
        });
      });
    });
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
@endpush