<template>
    <div class="container-fluid navbars pr-3"
         style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '';">
                    <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                        <li class="breadcrumb-item  active ">
                            <a href="{{ route('admin.modulus') }}">
                                <img src="/img/icons/resume.png">
                                <br>
                                <span class="nav-link-text ms-1">
                                    Packages
                                </span>
                            </a>
                        </li>
                        <li class="breadcrumb-item  active ">
                            <a href="{{ route('admin.modulus') }}">
                                <img src="/img/icons/resume.png">
                                <br>
                                <span class="nav-link-text ms-1">
                                    Addons
                                </span>
                            </a>
                        </li>
                        <li class="breadcrumb-item  active ">
                            <a href="{{ route('admin.modulus') }}">
                                <img src="/img/icons/resume.png">
                                <br>
                                <span class="nav-link-text ms-1">
                                    Payments
                                </span>
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'topMenu',
}
</script>
