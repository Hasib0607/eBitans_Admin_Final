$("#adminmobileappsmonth").change(function () {
    let m= $("#adminmobileappsmonth :selected").attr('value');
    let mt=100*Number(m);
    var addons=$('input[name = addons]').val();
    var mbm=$('input[name = mbm]').val();
    let amt=Number(addons)-(100*Number(mbm));
    amt=amt+mt;
    $("#addonsssss").html(amt);
    $('input[name = addons]').val(amt);
    var totals=$('input[name = total4]').val();
    // var totala=Number(totals)-(100*Number(mbm));
    var total=Number(totals)+amt;
    $("#finaltotal").html(total); 
    $("#subtotaltotal").html(total); 
    $("#finaltotal1").html(total); 
    $("#finaltotal12").html(total);
    $('input[name = total]').val(total);
    $('input[name = mbm]').val(m);
    debugger;
});

 $("#mobileappsmonthsa").change(function () {
     debugger;
    var name="mobileapps";
    var month=$("#mobileappsmonth").find(':selected').val();
    $.get('addonsadd',{name:name,month:month},function(){
        $('#load').load(location.href + ' .load');
        $('#load1').load(location.href + ' .load1');
        console.log("output select In")
    });
    //  valueChanged();
    // let m= $("#mobileappsmonth :selected").attr('value');
    // let mt=100*Number(m);
    // var addons=$('input[name = addons]').val();
    // var ambm=$('input[name = ambm]').val();
    // let amt=Number(addons)-(100*Number(ambm));
    // amt=amt+mt;
    // $("#addonsssss").html(amt);
    // $('input[name = addons]').val(amt);
    // var totals=$('input[name = total4]').val();
    // // var totala=Number(totals)-(100*Number(ambm));
    // var total=Number(totals)+amt;
    // $("#finaltotal").html(total); 
    // $("#finaltotal1").html(total); 
    // $("#subtotaltotal").html(total); 
    // $("#finaltotal12").html(total);
    // $('input[name = total]').val(total);
    // $('input[name = ambm]').val(m);
    // debugger;
});
 $("#activitymonth").change(function () {
     debugger;
    var name="activitylog";
    var month=$("#activitymonth").find(':selected').val();
    $.get('activityaddonsadd',{name:name,month:month},function(){
        $('#load').load(location.href + ' .load');
        $('#load1').load(location.href + ' .load1');
        console.log("output select In")
    });
});

    function changePack(){
        if($('.packdet').is(":checked")){   
            $('#packagedetails').addClass('activeds');
            $('input[name = selectpackage]').val("1");
            $("#packagenamessss").addClass("d-flex");
            $("#packagenamessss").removeClass("d-none");
            let subtotals=$('input[name = subtotal]').val();
            var totals=$('input[name = total4]').val();
            var total5=$('input[name = total5]').val();
            var discount=$('input[name = discount]').val();
            var addons=$('input[name = addons]').val();
            var total=Number(totals)+Number(addons);
            var total=Number(total5)+total;
            var subtota=Number(discount)+total;
            $("#subtotaltotal").html(subtota);
            $("#discount").html(discount);
            $("#finaltotal").html(total);
            $("#addonsssss").html(addons);
            $("#finaltotal1").html(total);
            $("#finaltotal12").html(total);
            $('input[name = total]').val(total);
            
            
            var plan_id=$("#plan_id").find(':selected').val();
            var month=$("#month").find(':selected').val();
            $.get('plancheck',{plan_id:plan_id,month:month},function(){
                $('#load').load(location.href + ' .load');
                $('#load1').load(location.href + ' .load1');
                console.log("output select")
            });
            
        }else{
            $('#packagedetails').removeClass('activeds');
            $('input[name = selectpackage]').val("0");
            $("#packagenamessss").removeClass("d-flex");
            $("#packagenamessss").addClass("d-none");
            var addons=$('input[name = addons]').val();
            $("#subtotaltotal").html(addons);
            $("#discount").html("0");
            $("#finaltotal").html(addons);
            $("#addonsssss").html(addons);
            $("#finaltotal1").html(addons);
            $("#finaltotal12").html(addons);
            $('input[name = total]').val("0");
            var plan_id=$("#plan_id").find(':selected').val();
            var month=$("#month").find(':selected').val();
            $.get('plancheckout',{plan_id:plan_id,month:month},function(){
                $('#load').load(location.href + ' .load');
                $('#load1').load(location.href + ' .load1');
                console.log("output select out")
            });
        }
    }
    function selectplan(strn)
    {
        if(strn=='plan'){
            $("#planss").addClass('actived');
            $("#packagedetails").show();
            $("#addonsdetails").hide();
            $("#addonss").removeClass('actived');
        }
        if(strn=='addons'){
            $("#addonss").addClass('actived');
            $("#planss").removeClass('actived');
            $("#packagedetails").hide();
            $("#addonsdetails").show();
        }
    }
    function selectaddons(){
        if($('#addonsss').is(":checked")){ 
            $("#addonss").addClass('actived');
        }else{
            $("#addonss").removeClass('actived');
        }
    }

    function valueChanged()
    {
        if($('.mobile').is(":checked")){   
            $("#mobile1").addClass('checkeds');
            // var addons=$('input[name = addons]').val();
            // addons=Number(addons)+100;
            // $('input[name = addons]').val("");
            // $('input[name = addons]').val(addons);
            // var totals=$('input[name = total]').val();
            // var totals4=$('input[name = total4]').val();
            // var totala=Number(totals);
            // var total=Number(totala)+100;
            // $("#addonsssss").html(addons);
            // $("#finaltotal").html(total);
            // $("#subtotaltotal").html(total); 
            // $("#finaltotal1").html(total); 
            // $("#finaltotal12").html(total);
            //  $('input[name = total]').val(total);
             $('#mobileappsmonth').show();
             var name="mobileapps";
            var month=$("#mobileappsmonth").find(':selected').val();
            $.get('addonsadd',{name:name,month:month},function(){
                $('#load').load(location.href + ' .load');
                $('#load1').load(location.href + ' .load1');
                console.log("output select In")
            });
        }else{
            $("#mobile1").removeClass('checkeds');
            $.get('addonsremove',function(){
                $('#load').load(location.href + ' .load');
                $('#load1').load(location.href + ' .load1');
                console.log("output select out")
            });
            // var addons=$('input[name = addons]').val();
            // addons=Number(addons)-100;
            // $('input[name = addons]').val("");
            // $('input[name = addons]').val(addons);
            // var totals=$('input[name = total]').val();
            // var totals4=$('input[name = total4]').val();
            // var totala=Number(totals);
            // if(totala==0){
            //     var total=addons;
            // }else{
            //     var total=Number(totala)-100;
            // }
            // $("#addonsssss").html(addons);
            // $("#finaltotal").html(total); 
            // $("#subtotaltotal").html(total); 
            // $("#finaltotal1").html(total); 
            // $("#finaltotal12").html(total);
            //  $('input[name = total]').val(total);
             $('#mobileappsmonth').hide();
        }
    }
    function valueChanged12()
    {
        if($('.adminpanel').is(":checked")){   
            $("#activity").addClass('checkeds');
             $('#activitymonth').show();
             var name="activitylog";
            var month=$("#activitymonth").find(':selected').val();
            $.get('activityaddonsadd',{name:name,month:month},function(){
                $('#load').load(location.href + ' .load');
                $('#load1').load(location.href + ' .load1');
                console.log("output select In")
            });
        }else{
            $("#activity").removeClass('checkeds');
            $.get('activityaddonsremove',function(){
                $('#load').load(location.href + ' .load');
                $('#load1').load(location.href + ' .load1');
                console.log("output select out")
            });
             $('#activitymonth').hide();
        }
    }
    function valueChanged1()
    {
        if($('.adminpanel').is(":checked")){   
            $("#adminpanel1").addClass('checkeds');
            var addons=$('input[name = addons]').val();
            addons=Number(addons)+100;
            $('input[name = addons]').val("");
            $('input[name = addons]').val(addons);
            var totals=$('input[name = total]').val();
            var totals4=$('input[name = total4]').val();
            var totala=Number(totals);
            var total=Number(totala)+100;
            $("#addonsssss").html(addons);
            $("#finaltotal").html(total); 
            $("#subtotaltotal").html(total); 
            $("#finaltotal1").html(total); 
            $("#finaltotal12").html(total);
             $('input[name = total]').val(total);
             $('#adminmobileappsmonth').show();
        }else{
            $("#adminpanel1").removeClass('checkeds');
            var addons=$('input[name = addons]').val();
            addons=Number(addons)-100;
            $('input[name = addons]').val("");
            $('input[name = addons]').val(addons);
            var totals=$('input[name = total]').val();
            var totals4=$('input[name = total4]').val();
            var totala=Number(totals);
            if(totala==0){
                var total=addons;
            }else{
                var total=Number(totala)-100;
            }
            $("#addonsssss").html(addons);
            $("#finaltotal").html(total); 
            $("#subtotaltotal").html(total); 
            $("#finaltotal1").html(total); 
            $("#finaltotal12").html(total);
             $('input[name = total]').val(total);
             $('#adminmobileappsmonth').hide();
        }
    }


$(document).ready(function(){
        $('#bkash').show();
        $('#bkash1').show();
        $('#nagad').hide();
        $('#nagad1').hide();
        $('#nagadsetps').hide();
    $("input[name='paymentMethod']:radio").change(function(){
        if($(this).val() == 'bkash')
        {
          $('#bkash').show();
          $('#bkash1').show();
          $('#nagad').hide();
          $('#nagad1').hide();
          $('#bkashsetps').show();
          $('#nagadsetps').hide();
        }
        else if($(this).val() == 'nagad')
        {
          $('#nagad').show();
          $('#bkash').hide();
          $('#nagad1').show();
          $('#bkash1').hide();
          $('#bkashsetps').hide();
          $('#nagadsetps').show();
        }
    });
});
    var permonth = $('input[name = permonth]').val();
    function getComboB(selectObject) {
        var value = selectObject.value;  
        console.log(value);
        $.get("/changeplanss", {value:value},function(data) {
            $('#load').load(location.href + ' .load');
            $('#load1').load(location.href + ' .load1');
            console.log("output select In")
        });
    }
    
    