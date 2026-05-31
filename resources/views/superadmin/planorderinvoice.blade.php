<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('css/invoice.css')}}">
    <title>Invoice</title>
  </head>
  <body>

<div class="page-content container">
    <div class="page-header text-blue-d2">
        <h1 class="page-title text-secondary-d1">
            Invoice
            <small class="page-info">
                <i class="fa fa-angle-double-right text-80"></i>
                ID: #
            </small>
        </h1>

        <div class="page-tools">
            <div class="action-buttons">
                <a class="btn bg-white btn-light mx-1px text-95" href="#" onclick="window.print()" data-title="Print">
                    <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                    Print
                </a>
                <!--<a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="PDF">-->
                <!--    <i class="mr-1 fa fa-file-pdf-o text-danger-m1 text-120 w-2"></i>-->
                <!--    Export-->
                <!--</a>-->
            </div>
        </div>
    </div>

    <div class="container px-0">
        <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center text-150">
                            <!--<i class="fa fa-book fa-2x text-success-m2 mr-1"></i>-->
                            <img src="" alt="" width="130px">
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />

                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <span class="text-sm text-grey-m2 align-middle">To:</span>
                            <span class="text-600 text-110 text-blue align-middle"></span>
                        </div>
                        <div class="text-grey-m2">
                            <div class="my-1">
                                
                            </div>
                            <div class="my-1">
                                
                            </div>
                            <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary" style="transform: rotate(20deg);"></i> <b class="text-600"></b></div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                        <hr class="d-sm-none" />
                        <div class="text-grey-m2">
                            <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                Invoice
                            </div>

                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">ID:</span> #</div>

                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Issue Date:</span> </div>

                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Total:</span> ৳</div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>

                <div class="mt-4">
                    <div class="row text-600 text-white bgc-default-tp1 py-25">
                        <div class="d-none d-sm-block col-1">#</div>
                        <div class="col-9 col-sm-5">Description</div>
                        <div class="d-none d-sm-block col-4 col-sm-2">Qty</div>
                        <div class="d-none d-sm-block col-sm-2">Unit Price</div>
                        <div class="col-2">Amount</div>
                    </div>

                    <div class="text-95 text-secondary-d3">
                        <div class="row mb-2 mb-sm-0 py-25  bgc-default-l4 ">
                            <div class="d-none d-sm-block col-1"></div>
                            <div class="col-9 col-sm-5"></div>
                            <div class="d-none d-sm-block col-2"></div>
                            <div class="d-none d-sm-block col-2 text-95">৳</div>
                            <div class="col-2 text-secondary-d2">৳</div>
                        </div>
                    </div>
                        <!--<div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">-->
                        <!--    <div class="d-none d-sm-block col-1">2</div>-->
                        <!--    <div class="col-9 col-sm-5">Web hosting</div>-->
                        <!--    <div class="d-none d-sm-block col-2">1</div>-->
                        <!--    <div class="d-none d-sm-block col-2 text-95">$15</div>-->
                        <!--    <div class="col-2 text-secondary-d2">$15</div>-->
                        <!--</div>-->

                        <!--<div class="row mb-2 mb-sm-0 py-25">-->
                        <!--    <div class="d-none d-sm-block col-1">3</div>-->
                        <!--    <div class="col-9 col-sm-5">Software development</div>-->
                        <!--    <div class="d-none d-sm-block col-2">--</div>-->
                        <!--    <div class="d-none d-sm-block col-2 text-95">$1,000</div>-->
                        <!--    <div class="col-2 text-secondary-d2">$1,000</div>-->
                        <!--</div>-->

                        <!--<div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">-->
                        <!--    <div class="d-none d-sm-block col-1">4</div>-->
                        <!--    <div class="col-9 col-sm-5">Consulting</div>-->
                        <!--    <div class="d-none d-sm-block col-2">1 Year</div>-->
                        <!--    <div class="d-none d-sm-block col-2 text-95">$500</div>-->
                        <!--    <div class="col-2 text-secondary-d2">$500</div>-->
                        <!--</div>-->
                    <div class="row border-b-2 brc-default-l2"></div>

                    <!-- or use a table instead -->
                    <!--
            <div class="table-responsive">
                <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                    <thead class="bg-none bgc-default-tp1">
                        <tr class="text-white">
                            <th class="opacity-2">#</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th width="140">Amount</th>
                        </tr>
                    </thead>

                    <tbody class="text-95 text-secondary-d3">
                        <tr></tr>
                        <tr>
                            <td>1</td>
                            <td>Domain registration</td>
                            <td>2</td>
                            <td class="text-95">$10</td>
                            <td class="text-secondary-d2">$20</td>
                        </tr> 
                    </tbody>
                </table>
            </div>
            -->

                    <div class="row mt-3">
                        <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                            <!--Extra note such as company or payment information...-->
                        </div>

                        <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    SubTotal
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1">৳</span>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    Discount
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">৳</span>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    Tax 
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">৳</span>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    Shipping
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">৳</span>
                                </div>
                            </div>

                            <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                <div class="col-7 text-right">
                                    Total Amount
                                </div>
                                <div class="col-5">
                                    <span class="text-150 text-success-d3 opacity-2">৳</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div>
                        
                        <span class="text-secondary-d1 text-105">Thank you for your business, For Any Kind of information <br> Call Us :   or Visit : <a href="" target="_blank"></a></span>
                        <!--<a href="#" class="btn btn-info btn-bold px-4 float-right mt-3 mt-lg-0">Pay Now</a>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>