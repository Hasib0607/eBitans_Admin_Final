<nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
    <ol class="breadcrumb" style="background-color:transparent;color:#fff">
        <li class="breadcrumb-item active">
            <a href="{{ URL::to('/') }}/superadmin/order-plan-request">
                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Plan Order Request
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ URL::to('/') }}/superadmin/planorderrequest/rejected">
                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Rejected Plan Order
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ URL::to('/') }}/superadmin/planorderrequest-today">
                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Today Plan Order
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ URL::to('/') }}/superadmin/invoiceorder">
                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Invoice Order Request
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ URL::to('/') }}/superadmin/allinvoiceorder">
                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Invoice Order
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('superadmin.customizerequest') }}">
                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Customize Request
            </a>
        </li>

        <li class="breadcrumb-item">
            <a href="{{ route('superadmin.modulus.request') }}">
                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Modulus Request
            </a>
        </li>


        <li class="breadcrumb-item">
            <a href="{{ route('superadmin.registrationFee') }}">
                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Registration Fee
            </a>
        </li>

    </ol>
</nav>
