<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Plan Choose</title>
  <link rel="stylesheet" href="{{asset('plan/main.css')}}" />
  <style>
/* Center the loader */
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 120px;
  height: 120px;
  margin: -76px 0 0 -76px;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}

#myDiv {
  display: none;
  text-align: center;
}
</style>
</head>

<body onload="myFunction()">
  <section class="container">
    <div class="price_table">
      <div class="card" style="
                background-image: url('../../../plan/Mask_1.png');
                background-size: cover;
                background-position: center;
              ">
      <div class="title">
        <h2>Small Plan</h2>
      </div>
        <div class="list">
          <ul>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Free Domain</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Monthly Bandwith 1GB</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>SSD Strorage 1TB</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>SSL Certificate</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Website SEO</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Google Analytics</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Messenger Live Chat</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Full Support</p></li>

          </ul>
        </div>
        <div class="price">
          <h2> <span style="font-weight: 100;">$</span>39</h2>
          <h3>permonth</h3>
        </div>
        <div class="btn_area">
        <a href="{{route('planactive',1)}}" class="btn" style="text-decoration:none">Active</a>
        </div>
      </div>
      <div class="card" style="
                background-image: url('../../../plan/Mask.png');
                background-size: cover;
                background-position: center;
              ">
      <div class="title">
        <h2>Medium Plan</h2>
      </div>
        <div class="list">
          <ul>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Free Domain</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Monthly Bandwith 1GB</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>SSD Strorage 1TB</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>SSL Certificate</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Website SEO</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Google Analytics</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Messenger Live Chat</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Full Support</p></li>

          </ul>
        </div>
        <div class="price">
          <h2><span style="font-weight: 100;">$</span>39</h2>
          <h3>permonth</h3>
        </div>
        <div class="btn_area">
          <a href="{{route('planactive',2)}}" class="btn" style="text-decoration:none">Active</a>
        </div>
      </div>
      <div class="card" style="
                background-image: url('../../../plan/Mask_2.png');
                background-size: cover;
                background-position: center;
              ">
      <div class="title">
        <h2>Large Plan</h2>
      </div>
        <div class="list">
          <ul>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Free Domain</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Monthly Bandwith 1GB</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>SSD Strorage 1TB</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>SSL Certificate</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Website SEO</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Google Analytics</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Messenger Live Chat</p></li>
            <li> <img width="20px" height="20px" src="{{asset('plan/ok.png')}}" alt=""> <p>Full Support</p></li>

          </ul>
        </div>
        <div class="price">
          <h2><span style="font-weight: 100;"></span>4000</h2>
          <h3>permonth</h3>
        </div>
        <div class="btn_area">
          <a href="{{route('planactive',3)}}" class="btn" style="text-decoration:none">Active</a>
        </div>
      </div>
    </div>
  </section>
  <div id="loader"></div>

<div style="display:none;" id="myDiv" class="animate-bottom">
  <h2>Tada!</h2>
  <p>Some text in my newly loaded page..</p>
</div>
<script>
var myVar;

function myFunction() {
  myVar = setTimeout(showPage, 3000);
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
}
</script>
</body>

</html>