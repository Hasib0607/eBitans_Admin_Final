function incrementValue()
{
    debugger;
    var value = parseInt(document.getElementById('number').value, 10);
    value = isNaN(value) ? 0 : value;
    if(value<10){
        value++;
            document.getElementById('number').value = value;
    }
}
function decrementValue()
{
    var value = parseInt(document.getElementById('number').value, 10);
    value = isNaN(value) ? 0 : value;
    if(value>1){
        value--;
            document.getElementById('number').value = value;
    }

}
var datetime = new Date();
console.log(datetime);
document.getElementById("time").textContent = datetime; 
function refreshTime() {
    const timeDisplay = document.getElementById("time");
    const dateString = new Date().toLocaleString();
    const formattedString = dateString.replace(", ", " - ");
    timeDisplay.textContent = formattedString;
  }
    setInterval(refreshTime, 1000);
var datetime = new Date().getDate();
console.log(datetime);
document.getElementById("time").textContent = datetime;
var datetime = new Date().getDay();
console.log(datetime);
document.getElementById("time").textContent = datetime;
var datetime = new Date().getFullYear();
console.log(datetime);
document.getElementById("time").textContent = datetime;
var datetime = new Date().getHours()+1;
console.log(datetime);
document.getElementById("time").textContent = datetime;
var datetime = new Date().getMilliseconds();
console.log(datetime);
document.getElementById("time").textContent = datetime;
var datetime = new Date().getMonth() + 1;
console.log(datetime);
document.getElementById("time").textContent = datetime;
var datetime = new Date().toDateString();
console.log(datetime);
document.getElementById("time").textContent = datetime;
var datetime = new Date().toLocaleTimeString();
console.log(datetime);
document.getElementById("time").textContent = datetime;

$(document).on('click', '#addtocart', function() {
    $url="/addcart";
    debugger;
    var product_id = $(this).data('id');
    debugger;
    $.get($url,{product_id:product_id}, function(data){
        console.log(data);
        // toastr.success('Cart Add Successfully !');   
        // window.location.reload();   
        $('#cartlistload').load(location.href + ' .cartload'); 
        $('#cartlistload1').load(location.href + ' .cartload1'); 
        $('#cartlistload2').load(location.href + ' .cartload2'); 
        $('#cartlistload3').load(location.href + ' .cartload3'); 
        $('#cartlistload4').load(location.href + ' .cartload4'); 
        $('#cartlistload5').load(location.href + ' .cartload5'); 
    });
});


$(document).on('click', '#removefromcart', function() {
    $url="/deletefromcart";
    var cart_id = $(this).data('id');
    console.log(cart_id);
    debugger;
    $.get($url,{cart_id:cart_id}, function(data){
        console.log(data);
        // window.location.reload();   
        $('#cartlistload').load(location.href + ' .cartload'); 
        $('#cartlistload1').load(location.href + ' .cartload1'); 
        $('#cartlistload2').load(location.href + ' .cartload2'); 
        $('#cartlistload3').load(location.href + ' .cartload3'); 
        $('#cartlistload4').load(location.href + ' .cartload4'); 
        $('#cartlistload5').load(location.href + ' .cartload5'); 
    });
});
$(document).on('click', '#incrementcart', function() {
    $url="/increment-cart";
    var cart_id = $(this).data('id');
    console.log(cart_id);
    debugger;
    $.get($url,{cart_id:cart_id}, function(data){
        console.log(data);
        // window.location.reload();   
        $('#cartlistload').load(location.href + ' .cartload'); 
        $('#cartlistload1').load(location.href + ' .cartload1'); 
        $('#cartlistload2').load(location.href + ' .cartload2'); 
        $('#cartlistload3').load(location.href + ' .cartload3'); 
        $('#cartlistload4').load(location.href + ' .cartload4'); 
        $('#cartlistload5').load(location.href + ' .cartload5'); 
    });
});
$(document).on('click', '#decrementcart', function() {
    $url="/decrement-cart";
    var cart_id = $(this).data('id');
    console.log(cart_id);
    debugger;
    $.get($url,{cart_id:cart_id}, function(data){
        console.log(data);
        // window.location.reload();   
        $('#cartlistload').load(location.href + ' .cartload'); 
        $('#cartlistload1').load(location.href + ' .cartload1'); 
        $('#cartlistload2').load(location.href + ' .cartload2'); 
        $('#cartlistload3').load(location.href + ' .cartload3'); 
        $('#cartlistload4').load(location.href + ' .cartload4'); 
        $('#cartlistload5').load(location.href + ' .cartload5'); 
    });
});
$("input[name='phone']").on("keyup",function () {
    $url="/searchcustomer";
    var mobile=$('input[name=phone]').val();
    if(mobile != ''){
           $.get($url,{mobile:mobile}, function(data){
               console.log(data);
               if(!jQuery.isEmptyObject(data)){
                $('input[name=name]').val(data.name);
                debugger;
                $('input[name=email]').val(data.email);
                $('#address').val(data.mobile);               
               }
           });
           }
    
});

// $(document).on('click', '#customersave', function() {
    var container = document.getElementById("exampleModal");
      var modal = new bootstrap.Modal(container);
//     modal.hide();
// });
document.getElementById("customersave").addEventListener("click", function () {
    debugger;
    var phone=$('input[name=phone]').val();
    var name=$('input[name=name]').val();
    var email=$('input[name=email]').val();
    var address=$('input[name=address]').val();
     $url="/savecustomer";
    debugger;
    $.get($url,{phone:phone,name:name,email:email,address:address}, function(data){
        debugger;
        console.log(data);
        $('#customer').load(location.href + ' .cusload'); 
    });
    modal.hide();
  });
  function toggleFullScreenMode () {
    if ((document.fullScreenElement && document.fullScreenElement !== null) ||
            (!document.mozFullScreen && !document.webkitIsFullScreen)) {
        if (document.documentElement.requestFullScreen) {
            document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
            document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}


  $("input[name='discount']").on("keyup",function () {
      debugger;
    var totalamount=$('input[name=totalamountss]').val();
    console.log(totalamount);
    debugger
    var discount=$('input[name=discount]').val();
    console.log(discount);
    var tax=$('input[name=tax]').val();
    console.log(tax);
    var shipping=$('input[name=shipping]').val();
    console.log(shipping);
    var othercharge=$('input[name=othercharge]').val();
    console.log(othercharge);
    var total=(parseInt(totalamount)+parseInt(tax)+parseInt(shipping)+parseInt(othercharge))-parseInt(discount);
    console.log(total);
    $('#totalpayable').html(total);
    $('#cartlistload2').html(total);
    $('#orderdiscount').html(discount);
    $('input[name=totaldiscount]').val(discount);
    $('#ordertax').html(tax);
    $('input[name=totaltax]').val(tax);
    $('#ordershipping').html(shipping);
    $('input[name=totalshipping]').val(shipping);
    $('#orderothercharge').html(othercharge);
    $('input[name=totalothercharge]').val(othercharge);
    $('#ordertotal').html(total);
    $('input[name=totalamount]').val(total);
    $('#orderdiscount1').html(discount);
    $('input[name=totaldiscount1]').val(discount);
    $('#ordertax1').html(tax);
    $('input[name=totaltax1]').val(tax);
    $('#ordershipping1').html(shipping);
    $('input[name=totalshipping1]').val(shipping);
    $('#orderothercharge1').html(othercharge);
    $('input[name=totalothercharge1]').val(othercharge);
    $('#ordertotal1').html(total);
    $('input[name=totalamount1]').val(total);
  });
  $("input[name='tax']").on("keyup",function () {
    var totalamount=$('input[name=totalamountss]').val();
    console.log(totalamount);
    var discount=$('input[name=discount]').val();
    console.log(discount);
    var tax=$('input[name=tax]').val();
    console.log(tax);
    var shipping=$('input[name=shipping]').val();
    console.log(shipping);
    var othercharge=$('input[name=othercharge]').val();
    console.log(othercharge);
    var total=(parseInt(totalamount)+parseInt(tax)+parseInt(shipping)+parseInt(othercharge))-parseInt(discount);
    console.log(total);
    $('#totalpayable').html(total);
    $('#cartlistload2').html(total);
    $('#orderdiscount').html(discount);
    $('input[name=totaldiscount]').val(discount);
    $('#ordertax').html(tax);
    $('input[name=totaltax]').val(tax);
    $('#ordershipping').html(shipping);
    $('input[name=totalshipping]').val(shipping);
    $('#orderothercharge').html(othercharge);
    $('input[name=totalothercharge]').val(othercharge);
    $('#ordertotal').html(total);
    $('input[name=totalamount]').val(total);
    $('#orderdiscount1').html(discount);
    $('input[name=totaldiscount1]').val(discount);
    $('#ordertax1').html(tax);
    $('input[name=totaltax1]').val(tax);
    $('#ordershipping1').html(shipping);
    $('input[name=totalshipping1]').val(shipping);
    $('#orderothercharge1').html(othercharge);
    $('input[name=totalothercharge1]').val(othercharge);
    $('#ordertotal1').html(total);
    $('input[name=totalamount1]').val(total);
  });
  $("input[name='shipping']").on("keyup",function () {
    var totalamount=$('input[name=totalamountss]').val();
    console.log(totalamount);
    var discount=$('input[name=discount]').val();
    console.log(discount);
    var tax=$('input[name=tax]').val();
    console.log(tax);
    var shipping=$('input[name=shipping]').val();
    console.log(shipping);
    var othercharge=$('input[name=othercharge]').val();
    console.log(othercharge);
    var total=(parseInt(totalamount)+parseInt(tax)+parseInt(shipping)+parseInt(othercharge))-parseInt(discount);
    console.log(total);
    $('#totalpayable').html(total);
    $('#cartlistload2').html(total);
    $('#orderdiscount').html(discount);
    $('input[name=totaldiscount]').val(discount);
    $('#ordertax').html(tax);
    $('input[name=totaltax]').val(tax);
    $('#ordershipping').html(shipping);
    $('input[name=totalshipping]').val(shipping);
    $('#orderothercharge').html(othercharge);
    $('input[name=totalothercharge]').val(othercharge);
    $('#ordertotal').html(total);
    $('input[name=totalamount]').val(total);
    $('#orderdiscount1').html(discount);
    $('input[name=totaldiscount1]').val(discount);
    $('#ordertax1').html(tax);
    $('input[name=totaltax1]').val(tax);
    $('#ordershipping1').html(shipping);
    $('input[name=totalshipping1]').val(shipping);
    $('#orderothercharge1').html(othercharge);
    $('input[name=totalothercharge1]').val(othercharge);
    $('#ordertotal1').html(total);
    $('input[name=totalamount1]').val(total);
  });
  $("input[name='othercharge']").on("keyup",function () {
    var totalamount=$('input[name=totalamountss]').val();
    console.log(totalamount);
    var discount=$('input[name=discount]').val();
    console.log(discount);
    var tax=$('input[name=tax]').val();
    console.log(tax);
    var shipping=$('input[name=shipping]').val();
    console.log(shipping);
    var othercharge=$('input[name=othercharge]').val();
    console.log(othercharge);
    var total=(parseInt(totalamount)+parseInt(tax)+parseInt(shipping)+parseInt(othercharge))-parseInt(discount);
    console.log(total);
    $('#totalpayable').html(total);
    $('#cartlistload2').html(total);
    $('#orderdiscount').html(discount);
    $('input[name=totaldiscount]').val(discount);
    $('#ordertax').html(tax);
    $('input[name=totaltax]').val(tax);
    $('#ordershipping').html(shipping);
    $('input[name=totalshipping]').val(shipping);
    $('#orderothercharge').html(othercharge);
    $('input[name=totalothercharge]').val(othercharge);
    $('#ordertotal').html(total);
    $('input[name=totalamount]').val(total);
    $('#orderdiscount1').html(discount);
    $('input[name=totaldiscount1]').val(discount);
    $('#ordertax1').html(tax);
    $('input[name=totaltax1]').val(tax);
    $('#ordershipping1').html(shipping);
    $('input[name=totalshipping1]').val(shipping);
    $('#orderothercharge1').html(othercharge);
    $('input[name=totalothercharge1]').val(othercharge);
    $('#ordertotal1').html(total);
    $('input[name=totalamount1]').val(total);
  });

  $(document).on('click', '#holdorderdetails', function() {
    $url="/holdorderdetails";
    var order_id = $(this).data('id');
    console.log(order_id);
    debugger;
    $.get($url,{order_id:order_id}, function(data){
        console.log(data);
        $( "#customerdetails" ).empty();
        $( "#customerdetails" ).append(data[0]);
        $( "#productlisthead" ).empty();
        $( "#productlisthead" ).append(data[1]);
        $( "#subtotals" ).empty();
        $( "#subtotals" ).append(data[2]);
        $( "#editorderbtn" ).empty();
        $( "#editorderbtn" ).append(data[3]);
        debugger;
    });
  });