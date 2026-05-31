<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pricing List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
<style>
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link{
        background-color: #f1593A !important;
    }
    .swiper {
        max-width: 860px;
        height: 100%;
      }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;

        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
      }
      .swiper-pagination{
        display: flex;
        bottom:0px !important;
      }
      .swiper-pagination-bullet{
        width:100%;
        border-radius: 0px !important;
      }
      .swiper-pagination-bullet-active {
        background-color: #f1593A !important;
        color:#fff !important;
      }
      .btn-ebitans{
        background-color:#f1593A !important;
        color:#fff;
      }
      .btn-ebitans:hover{
        background-color:#f98c76 !important;
        color:#fff;
      }
      .nav-pills .nav-link.active{
          color:#fff !important;
      }
      .nav-link{
          color:#f1593A !important;
      }


</style>
</head>
  <body>
    <div class="container">
        <h1 class="text-center mt-3 fs-8">Choose Your Plan</h1>
        <div class="row">
            <div class="col-12 shadow-lg rounded">
                <ul class="nav nav-pills mb-3 d-flex flex-row justify-content-center py-4" id="pills-tab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Monthly</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">6 Month</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Yearly</button>
                  </li>
                </ul>
                <div class="tab-content row d-flex flex-wrap flex-row w-100 justify-content-evenly" id="pills-tabContent">
                    <div class="col-4">
                        <div class="col-md-12" id="fixed">
                            <div style="height:100px">
                                <h1 class="fs-3 text-center">AVAILABLE PRICING PLANS</h1>                                
                            </div>
                            <hr>
                            <p class="fs-6 text-center">Add Domain</p>
                            <hr>
                            <p class="fs-6 text-center">Unlimited Hosting</p>
                            <hr>
                            <p class="fs-6 text-center">React Website</p>
                            <hr>
                            <p class="fs-6 text-center">Branch</p>
                            <hr>
                            <p class="fs-6 text-center">Product</p>
                            <hr>
                            <p class="fs-6 text-center">Staff</p>
                            <hr>
                            <!--<p class="fs-6 text-center">Google Ads</p>-->
                            <!--<hr>-->
                            <p class="fs-6 text-center">Busniess ERP</p>
                            <hr>
                        </div>
                    </div>
                    <div class="col-8" id="home">
                        <div class="swiper">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                              <!-- Slides -->
                              @if(isset($plans) && count($plans)>0)
                              @foreach($plans as $key=>$plan)
                                <div class="swiper-slide">
                                        <div class="col-md-12" id="home1">
                                            <div style="height:100px">
                                            <h1 class="fs-3 text-center">{{$plan->name}}</h1>
                                            <?php
                                            if($plan->discount_type=='fixed'){
                                                $discount=$plan->price-$plan->onedis;
                                            }elseif($plan->discount_type=='percent'){
                                                $discount=$plan->price-($plan->price*($plan->onedis/100));
                                            }else{
                                                $discount=0;
                                            }
                                            ?>
                                            @if($plan->onedis>0)
                                            <h3 class="fs-6 text-center">BDT {{$discount}}&nbsp;<del style="color:red;font-size:13px">{{$plan->price}}</del> &nbsp;/ <span class="text-mute fs-6">MONTHY</span></h3>
                                            <p class="text-center" style="font-size:14px;color:green">Save {{$plan->onedis}} @if($plan->discount_type=='percent') % @endif</p>
                                            @else
                                            <h3 class="fs-6 text-center">BDT {{$plan->price}}/ <span class="text-mute fs-6">MONTHY</span></h3>
                                            @endif
                                            </div>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->branch))
                                                @if($plan->branch=='0')
                                                0
                                                @else
                                                {{$plan->branch}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->product))
                                                @if($plan->product=='0')
                                                Unlimited
                                                @else
                                                {{$plan->product}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->staff))
                                                @if($plan->staff=='0')
                                                Unlimited
                                                @else
                                                {{$plan->staff}}
                                                @endif
                                                
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->google_ad))
                                                @if($plan->google_ad=='0')
                                                Unlimited
                                                @else
                                                {{$plan->google_ad}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <!--<p class="fs-6 text-center">-->
                                            <!--    <i class="fa fa-check"></i>-->
                                            <!--</p>-->
                                            <!--<hr>-->
                                            <p class="fs-6 text-center py-4">
                                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/1" class="btn btn-ebitans"> Active </a>
                                            </p>
                                        </div>
                                </div>
                              @endforeach
                              @endif
                            </div>
                            <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>
                          
                            <!-- If we need navigation buttons -->
                            <!-- <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div> -->
                          
                            <!-- If we need scrollbar -->
                            <div class="swiper-scrollbar"></div>
                        </div>
                        
                    </div>
                    <div class="col-8" id="profile">
                        <div class="swiper">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                              <!-- Slides -->
                                @if(isset($plans) && count($plans)>0)
                              @foreach($plans as $key=>$plan)
                                <div class="swiper-slide">
                                        <div class="col-md-12" id="home1">
                                            <div style="height:100px">
                                            <?php
                                            $price=$plan->price;
                                            $discount_price=$plan->sixdis;
                                            if($plan->discount_type=='fixed'){
                                                $discount=$price-$discount_price;
                                            }elseif($plan->discount_type=='percent'){
                                                $discount=$price-($price*($discount_price/100));
                                            }else{
                                                $discount=0;
                                            }
                                            ?>
                                            <h1 class="fs-3 text-center">{{$plan->name}}</h1>
                                            @if($plan->sixdis>0)
                                            <h3 class="fs-6 text-center">BDT {{$discount}}&nbsp;<del style="color:red;font-size:13px">{{$plan->price}}</del>&nbsp;/ <span class="text-mute fs-6">MONTHY</span></h3>
                                            <p class="text-center" style="font-size:14px;color:green">Save {{$discount_price}} @if($plan->discount_type=='percent') % @endif</p>
                                            @else
                                            <h3 class="fs-6 text-center">BDT {{$plan->price}}/ <span class="text-mute fs-6">MONTHY</span></h3>
                                            @endif
                                            
                                            </div>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                                                                        <p class="fs-6 text-center">
                                                @if(isset($plan->branch))
                                                @if($plan->branch=='0')
                                                0
                                                @else
                                                {{$plan->branch}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->product))
                                                @if($plan->product=='0')
                                                Unlimited
                                                @else
                                                {{$plan->product}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->staff))
                                                @if($plan->staff=='0')
                                                Unlimited
                                                @else
                                                {{$plan->staff}}
                                                @endif
                                                
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->google_ad))
                                                @if($plan->google_ad=='0')
                                                Unlimited
                                                @else
                                                {{$plan->google_ad}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <!--<p class="fs-6 text-center">-->
                                            <!--    <i class="fa fa-check"></i>-->
                                            <!--</p>-->
                                            <!--<hr>-->
                                            <p class="fs-6 text-center py-4">
                                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/6" class="btn btn-ebitans"> Active </a>
                                            </p>
                                        </div>
                                </div>
                              @endforeach
                              @endif
                            </div>
                            <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>
                          
                            <!-- If we need navigation buttons -->
                            <!-- <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div> -->
                          
                            <!-- If we need scrollbar -->
                            <div class="swiper-scrollbar"></div>
                        </div>
                    </div>
                    <div class="col-8" id="contact">
                        <div class="swiper">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                              <!-- Slides -->
                                @if(isset($plans) && count($plans)>0)
                              @foreach($plans as $key=>$plan)
                                <div class="swiper-slide">
                                        <div class="col-md-12" id="home1">
                                            <div style="height:100px">
                                                <?php
                                                $price=$plan->price;
                                                $discount_price=$plan->twelvedis;
                                            if($plan->discount_type=='fixed'){
                                                $discount=$price-$discount_price;
                                            }elseif($plan->discount_type=='percent'){
                                                $discount=$price-($price*($discount_price/100));
                                            }else{
                                                $discount=0;
                                            }
                                            ?>
                                            <h1 class="fs-3 text-center">{{$plan->name}}</h1>
                                            @if($plan->twelvedis>0)
                                            <h3 class="fs-6 text-center">BDT {{$discount}}&nbsp;<del style="color:red;font-size:13px;">{{$plan->price}}</del>&nbsp;/ <span class="text-mute fs-6">MONTHY</span></h3>
                                            <p class="text-center" style="font-size:14px;color:green">Save {{$discount_price}} @if($plan->discount_type=='percent') % @endif</p>
                                            @else
                                            <h3 class="fs-6 text-center">BDT {{$plan->price}}/ <span class="text-mute fs-6">MONTHY</span></h3>
                                            @endif
                                            
                                            </div>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                <i class="fa fa-check"></i>
                                            </p>
                                            <hr>
                                                                                        <p class="fs-6 text-center">
                                                @if(isset($plan->branch))
                                                @if($plan->branch=='0')
                                                0
                                                @else
                                                {{$plan->branch}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->product))
                                                @if($plan->product=='0')
                                                Unlimited
                                                @else
                                                {{$plan->product}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->staff))
                                                @if($plan->staff=='0')
                                                Unlimited
                                                @else
                                                {{$plan->staff}}
                                                @endif
                                                
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <p class="fs-6 text-center">
                                                @if(isset($plan->google_ad))
                                                @if($plan->google_ad=='0')
                                                Unlimited
                                                @else
                                                {{$plan->google_ad}}
                                                @endif
                                                @else
                                                <i class="fa fa-xmark" style="color:red"></i>
                                                @endif
                                            </p>
                                            <hr>
                                            <!--<p class="fs-6 text-center">-->
                                            <!--    <i class="fa fa-check"></i>-->
                                            <!--</p>-->
                                            <!--<hr>-->
                                            <p class="fs-6 text-center py-4">
                                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/12" class="btn btn-ebitans"> Active </a>
                                            </p>
                                        </div>
                                </div>
                              @endforeach
                              @endif
                            </div>
                            <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>
                          
                            <!-- If we need navigation buttons -->
                            <!-- <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div> -->
                          
                            <!-- If we need scrollbar -->
                            <div class="swiper-scrollbar"></div>
                        </div>
                    </div>  

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <script>
        const swiper = new Swiper('.swiper', {
            // Optional parameters
            slidesPerView: 3,
            speed: 400,
            spaceBetween: 10,
            direction: 'horizontal',
            loop: true,

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            // scrollbar: {
            //     el: '.swiper-scrollbar',
            //     clickable: true,
            // },
            breakpoints: {
            // when window width is >= 320px
            320: {
            slidesPerView: 1,
            spaceBetween: 10
            },
            // when window width is >= 480px
            480: {
            slidesPerView: 1,
            spaceBetween: 10
            },
            // when window width is >= 640px
            640: {
            slidesPerView: 3,
            spaceBetween: 10
            }
        }

            // And if we need scrollbar
        });
            $('#home').show();
            $('#profile').hide();
            $('#contact').hide();
        $('#pills-profile-tab').on('click',function(){
            debugger;
            $('#home').hide();
            $('#profile').show();
            $('#contact').hide();
        });
        $('#pills-home-tab').on('click',function(){
            debugger;
            $('#home').show();
            $('#profile').hide();
            $('#contact').hide();
        });
        $('#pills-contact-tab').on('click',function(){
            debugger;
            $('#home').hide();
            $('#profile').hide();
            $('#contact').show();
        });
    </script>
</body>
</html>
