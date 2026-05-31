<!DOCTYPE html>
<html>
<head>
  <title>Ebitans</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-lg-12" style="margin-top: 15px ">
        <div class="pull-left">
          <h2>Ebitans</h2>
        </div>
        <div class="pull-right">
        </div>
      </div>
    </div><br>

    <table class="table table-bordered">
      <tr>
        <th>Name</th>
        <th>SKU</th>
        <th>Bar Code</th>
      </tr>

      @foreach ($products as $product)
      <tr>
        <td align="center">
            {{$product->name}} <br>
            <strong style="font-size: 12px;">{{$product->regular_price}}TK</strong>
        </td>
        <td>{{$product->SKU}}</td>
        <td>@if(isset($product->barcode) && $product->barcode != "")
            <div class="barcode">{!! DNS1D::getBarcodeHTML(ucwords($product->barcode), "C128",1.9,32) !!}</div>
            @endif
        </td>
      </tr>
      @endforeach
    </table>
  </div>
</body>
</html>
