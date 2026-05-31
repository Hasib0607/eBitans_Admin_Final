<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pricing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active{
            background-color: #f1593a !important;
            color:#fff !important;
            border-radius:9999px !important;
        }
        .active p{
            color:#fff !important;
        }
    </style>
  </head>
  <body>
    <div class="container mx-auto">
        <h1 class="text-center text-2xl md:text-4xl font-bold pb-10 mt-4">
        Choose Your Package
        </h1>
        <?php
        $premium=DB::table('plans')->where('name','Premium')->first();
        ?>
        <div class="flex justify-center">
            <div class="flex flex-wrap items-center justify-center mb-12 sm:bg-gray-200 rounded-full sm:overflow-hidden p-1">
                <div>
                    <button class="active text-black font-semibold py-2 px-4" id="1month">1 Month</button>
                </div>
                <div>
                    <button class="text-black font-semibold py-2 px-4" id="6month">6 Month<p class="text-green-600 text-center text-sm inline">(Save {{$premium->sixdis}} %)</p></button>
                </div>
                <div>
                    <button class="text-black font-semibold py-2 px-4" id="12month">12 Month<p class="text-green-600 text-center text-sm inline">(Save {{$premium->twelvedis}} %)</p></button>
                </div>
            </div>
        </div>
        <div class="grid gap-0 grid-cols-1 md:grid-cols-3 px-10">
            @if(isset($plans) && count($plans)>0)
            @foreach($plans as $key=>$plan)
            @if($plan->status=='active' && $key=='0')
            <!-- level 1 1month start -->
            <div data-index="0" id="1monthdiv" class="1monthdiv slick-slide" tabindex="-1" aria-hidden="true" style="outline: none; width: 100%">
                <?php
                if($plan->discount_type=='fixed'){
                    $discount=$plan->price-$plan->onedis;
                }elseif($plan->discount_type=='percent'){
                    $discount=$plan->price-($plan->price*($plan->onedis/100));
                }else{
                    $discount=0;
                }
                ?>
                <div>
                    <div class="border-2 lg2:border-r-0 pt-8 h-[525px]" tabindex="-1" style="width: 100%; display: inline-block">
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">{{$plan->name}}</h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                        Get everything you need to create your e-commerce website, and
                                        have a user-friendly inventory management solution.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">Save {{$plan->onedis}} @if($plan->discount_type=='percent') % @endif</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$discount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Basic:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path
                                            d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"
                                            ></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                            0
                                        @else
                                            {{$plan->branch}}
                                        @endif
                                        @endif 
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/1">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- leevel 1 1month end -->
            <!-- level 1 6month start -->
            <div data-index="0" id="6monthdiv" style="display:none" class="6monthdiv slick-slide" tabindex="-1" aria-hidden="true" style="outline: none; width: 100%">
                <?php
                if($plan->discount_type=='fixed'){
                    $a1discount=$plan->price-$plan->sixdis;
                }elseif($plan->discount_type=='percent'){
                    $a1discount=$plan->price-($plan->price*($plan->sixdis/100));
                }else{
                    $a1discount=0;
                }
                ?>
                <div>
                    <div class="border-2 lg2:border-r-0 pt-8 h-[525px]" tabindex="-1" style="width: 100%; display: inline-block">
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">{{$plan->name}}</h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                        Get everything you need to create your e-commerce website, and
                                        have a user-friendly inventory management solution.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">Save {{$plan->sixdis}} @if($plan->discount_type=='percent') % @endif</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$a1discount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Basic:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path
                                            d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"
                                            ></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                            0
                                        @else
                                            {{$plan->branch}}
                                        @endif
                                        @endif 
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/6">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- leevel 1 6month end -->
            <!-- level 1 12month start -->
            <div data-index="0" id="12monthdiv" style="display:none" class="12monthdiv slick-slide" tabindex="-1" aria-hidden="true" style="outline: none; width: 100%">
                <?php
                if($plan->discount_type=='fixed'){
                    $a12discount=$plan->price-$plan->twelvedis;
                }elseif($plan->discount_type=='percent'){
                    $a12discount=$plan->price-($plan->price*($plan->twelvedis/100));
                }else{
                    $a12discount=0;
                }
                ?>
                <div>
                    <div class="border-2 lg2:border-r-0 pt-8 h-[525px]" tabindex="-1" style="width: 100%; display: inline-block">
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">{{$plan->name}}</h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                        Get everything you need to create your e-commerce website, and
                                        have a user-friendly inventory management solution.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">Save {{$plan->twelvedis}} @if($plan->discount_type=='percent') % @endif</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$a12discount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Basic:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path
                                            d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"
                                            ></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                            0
                                        @else
                                            {{$plan->branch}}
                                        @endif
                                        @endif 
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/12">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- leevel 1 12month end -->
            @endif
            @endforeach
            @endif
            
            @if(isset($plans) && count($plans)>0)
            @foreach($plans as $key=>$plan)
            @if($plan->status=='active' && $key=='1')
            <!-- level 2 1 month start -->
            <div data-index="1" id="1monthdiv" class="1monthdiv slick-slide" tabindex="-1" aria-hidden="true" style="outline:none; width:100%">
                <?php
                $price=$plan->price;
                $discount_price=$plan->onedis;
                if($plan->discount_type=='fixed'){
                    $discount=$price-$discount_price;
                }elseif($plan->discount_type=='percent'){
                    $discount=$price-($price*($discount_price/100));
                }else{
                    $discount=0;
                }
                ?>
                <div>
                    <div class="lg2:border-b-2 lg2:border-t-2 border-2 lg2:border-0 h-[525px] bg-[#ffedea] pt-8 relative shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)]" tabindex="-1" style="width: 100%; display: inline-block">
                        <p class="bg-[#f06448d8] text-white text-sm font-semibold text-center w-full absolute z-[5] -top-1 border-[#f06448d8] py-1">
                            Most Popular
                        </p>
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">
                                    {{$plan->name}}
                                </h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                    Level up your business with professional sale &amp; stock
                                    reporting and access staff accounts to run effective branch
                                    operations.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">( Save {{$discount_price}} @if($plan->discount_type=='percent') % @endif )</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$discount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Standard:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                        0
                                        @else
                                        {{$plan->branch}}
                                        @endif
                                        @endif
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif 
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/1">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- level 2 1 month end -->
            <!-- level 2 6 month start -->
            <div data-index="1" id="6monthdiv" style="display:none" class="6monthdiv slick-slide" tabindex="-1" aria-hidden="true" style="outline:none; width:100%">
                <?php
                $bprice=$plan->price;
                $bdiscount_price=$plan->sixdis;
                if($plan->discount_type=='fixed'){
                    $bdiscount=$bprice-$bdiscount_price;
                }elseif($plan->discount_type=='percent'){
                    $bdiscount=$bprice-($bprice*($bdiscount_price/100));
                }else{
                    $bdiscount=0;
                }
                ?>
                <div>
                    <div class="lg2:border-b-2 lg2:border-t-2 border-2 lg2:border-0 h-[525px] bg-[#ffedea] pt-8 relative shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)]" tabindex="-1" style="width: 100%; display: inline-block">
                        <p class="bg-[#f06448d8] text-white text-sm font-semibold text-center w-full absolute z-[5] -top-1 border-[#f06448d8] py-1">
                            Most Popular
                        </p>
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">
                                    {{$plan->name}}
                                </h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                    Level up your business with professional sale &amp; stock
                                    reporting and access staff accounts to run effective branch
                                    operations.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">( Save {{$bdiscount_price}} @if($plan->discount_type=='percent') % @endif )</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$bdiscount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Standard:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                        0
                                        @else
                                        {{$plan->branch}}
                                        @endif
                                        @endif
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif 
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/6">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- level 2 6 month end -->
            <!-- level 2 12 month start -->
            <div data-index="1" id="12monthdiv" style="display:none" class="12monthdiv slick-slide" tabindex="-1" aria-hidden="true" style="outline:none; width:100%">
                <?php
                $cprice=$plan->price;
                $cdiscount_price=$plan->twelvedis;
                if($plan->discount_type=='fixed'){
                    $cdiscount=$cprice-$cdiscount_price;
                }elseif($plan->discount_type=='percent'){
                    $cdiscount=$cprice-($cprice*($cdiscount_price/100));
                }else{
                    $bdiscount=0;
                }
                ?>
                <div>
                    <div class="lg2:border-b-2 lg2:border-t-2 border-2 lg2:border-0 h-[525px] bg-[#ffedea] pt-8 relative shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)]" tabindex="-1" style="width: 100%; display: inline-block">
                        <p class="bg-[#f06448d8] text-white text-sm font-semibold text-center w-full absolute z-[5] -top-1 border-[#f06448d8] py-1">
                            Most Popular
                        </p>
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">
                                    {{$plan->name}}
                                </h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                    Level up your business with professional sale &amp; stock
                                    reporting and access staff accounts to run effective branch
                                    operations.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">( Save {{$cdiscount_price}} @if($plan->discount_type=='percent') % @endif )</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$cdiscount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Standard:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                        0
                                        @else
                                        {{$plan->branch}}
                                        @endif
                                        @endif
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif 
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/12">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- level 2 12 month end -->
            @endif
            @endforeach
            @endif
            
            @if(isset($plans) && count($plans)>0)
            @foreach($plans as $key=>$plan)
            @if($plan->status=='active' && $key=='2')
            <!-- level 3 1month start -->
            <div data-index="2" id="1monthdiv" class="1monthdiv slick-slide slick-active" tabindex="-1" aria-hidden="false" style="outline: none; width:100%">
                    <?php
                        $price=$plan->price;
                        $discount_price=$plan->onedis;
                    if($plan->discount_type=='fixed'){
                        $discount=$price-$discount_price;
                    }elseif($plan->discount_type=='percent'){
                        $discount=$price-($price*($discount_price/100));
                    }else{
                        $discount=0;
                    }
                    ?>
                <div>
                    <div class="border-2 lg2:border-l-0 pt-8 h-[525px]" tabindex="-1" style="width: 100%; display: inline-block">
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">
                                    {{$plan->name}}
                                </h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                    Get the best of eBitans by manage a huge number of products
                                    and run 5 more outlet operations.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">( Save {{$discount_price}} @if($plan->discount_type=='percent') % @endif )</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$discount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Premium:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                        0
                                        @else
                                        {{$plan->branch}}
                                        @endif
                                        @endif
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif 
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/1">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- level 3 1month end-->
            <!-- level 3 6month start -->
            <div data-index="2" id="6monthdiv" style="display:none" class="6monthdiv slick-slide slick-active" tabindex="-1" aria-hidden="false" style="outline: none; width:100%">
                    <?php
                        $bprice=$plan->price;
                        $bdiscount_price=$plan->sixdis;
                    if($plan->discount_type=='fixed'){
                        $bdiscount=$bprice-$bdiscount_price;
                    }elseif($plan->discount_type=='percent'){
                        $bdiscount=$bprice-($bprice*($bdiscount_price/100));
                    }else{
                        $bdiscount=0;
                    }
                    ?>
                <div>
                    <div class="border-2 lg2:border-l-0 pt-8 h-[525px]" tabindex="-1" style="width: 100%; display: inline-block">
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">
                                    {{$plan->name}}
                                </h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                    Get the best of eBitans by manage a huge number of products
                                    and run 5 more outlet operations.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">( Save {{$bdiscount_price}} @if($plan->discount_type=='percent') % @endif )</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$bdiscount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Premium:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                        0
                                        @else
                                        {{$plan->branch}}
                                        @endif
                                        @endif
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif 
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/6">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- level 3 6month end-->
            <!-- level 3 12 month start -->
            <div data-index="2" id="12monthdiv" style="display:none" class="12monthdiv slick-slide slick-active" tabindex="-1" aria-hidden="false" style="outline: none; width:100%">
                    <?php
                        $cprice=$plan->price;
                        $cdiscount_price=$plan->twelvedis;
                    if($plan->discount_type=='fixed'){
                        $cdiscount=$cprice-$cdiscount_price;
                    }elseif($plan->discount_type=='percent'){
                        $cdiscount=$cprice-($cprice*($cdiscount_price/100));
                    }else{
                        $cdiscount=0;
                    }
                    ?>
                <div>
                    <div class="border-2 lg2:border-l-0 pt-8 h-[525px]" tabindex="-1" style="width: 100%; display: inline-block">
                        <div class="w-full space-y-4">
                            <div class="min-h-[150px] mx-2 sm:mx-10 xl:mx-20 border-b border-gray-400 text-center">
                                <h6 class="text-[28px] font-semibold text-center capitalize">
                                    {{$plan->name}}
                                </h6>
                                <div>
                                    <p class="text-sm text-gray-500 py-1">
                                    Get the best of eBitans by manage a huge number of products
                                    and run 5 more outlet operations.
                                    </p>
                                </div>
                                <div>
                                    <div class="flex gap-x-3 justify-center items-center pt-3">
                                        <p class="text-base line-through text-red-600">{{$plan->price}}</p>
                                        <p class="text-center text-green-600">( Save {{$cdiscount_price}} @if($plan->discount_type=='percent') % @endif )</p>
                                    </div>
                                    <div class="flex justify-center py-4">
                                        <p>BDT</p>
                                        <p class="text-3xl">{{$cdiscount}}<span class="text-sm">/Month</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 sm:px-10 xl:px-20">
                                <h1 class="uppercase text-sm font-bold">
                                    WHAT’S INCLUDED ON Premium:
                                </h1>
                            </div>
                            <div class="py-3 mx-2 sm:mx-10 xl:mx-20">
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path fill="none" d="M0 0h24v24H0z"></path>
                                            <path d="M7.105 15.21A3.001 3.001 0 1 1 5 15.17V8.83a3.001 3.001 0 1 1 2 0V12c.836-.628 1.874-1 3-1h4a3.001 3.001 0 0 0 2.895-2.21 3.001 3.001 0 1 1 2.032.064A5.001 5.001 0 0 1 14 13h-4a3.001 3.001 0 0 0-2.895 2.21z"></path>
                                        </g>
                                    </svg>
                                    <p>
                                        @if(isset($plan->branch))
                                        @if($plan->branch=='0')
                                        0
                                        @else
                                        {{$plan->branch}}
                                        @endif
                                        @endif
                                        Branch (POS)
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->product))
                                        @if($plan->product=='0')
                                        Unlimited
                                        @else
                                        {{$plan->product}}
                                        @endif
                                        @endif 
                                        Products
                                    </p>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" class="text-[#f1593a]" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path>
                                    </svg>
                                    <p>
                                        @if(isset($plan->staff))
                                        @if($plan->staff=='0')
                                        Unlimited
                                        @else
                                        {{$plan->staff}}
                                        @endif
                                        @endif
                                        Staffs
                                    </p>
                                </div>
                            </div>
                            <div class="mx-2 sm:mx-10 xl:mx-20 pb-8">
                                <a href="{{URL::to('/')}}/active-plan/{{$plan->id}}/12">
                                    <p class="text-white text-center w-full rounded-md py-2 drop-shadow-lg shadow-md font-normal hover:text-[#f1593a] border-[#f1593a] bg-[#f1593a] hover:bg-[#fff] border-2 outline-0">
                                    Active 7 days Free Trial
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- level 3 12 month end-->
            @endif
            @endforeach
            @endif
        </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        $('#6monthdiv').hide();
        $('#12monthdiv').hide();
        $('#6month').on('click',function(){
            $('#1month').removeClass('active');
            $('#12month').removeClass('active');
            $('#6month').addClass('active');
            $('.1monthdiv').hide();
            $('.6monthdiv').show();
            $('.12monthdiv').hide();
        })
        $('#1month').on('click',function(){
            $('#1month').addClass('active');
            $('#12month').removeClass('active');
            $('#6month').removeClass('active');
            $('.1monthdiv').show();
            $('.6monthdiv').hide();
            $('.12monthdiv').hide();
        })
        $('#12month').on('click',function(){
            $('#1month').removeClass('active');
            $('#12month').addClass('active');
            $('#6month').removeClass('active');
            $('.1monthdiv').hide();
            $('.6monthdiv').hide();
            $('.12monthdiv').show();
        })
    </script>
  </body>
</html>
