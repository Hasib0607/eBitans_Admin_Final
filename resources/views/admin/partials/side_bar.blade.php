<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-2 fixed-start ms-2 bg-gradient-dark bg-white"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="analytics.html" target="_blank">
            <img src="{{ asset('logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        </a>
    </div>

    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link text-white active"
                    aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">dashboard</i>
                    <span class="nav-link-text ms-2 ps-1">Dashboards</span>
                </a>
                <div class="collapse  show " id="dashboardsExamples">
                    <ul class="nav ">
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="analytics.html">
                                <span class="sidenav-mini-icon"> A </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Analytics </span>
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link text-white active" href="discover.html">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Discover </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="sales.html">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Sales </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="automotive.html">
                                <span class="sidenav-mini-icon"> A </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Automotive </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="smart-home.html">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Smart Home </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
             
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#pagesExamples" class="nav-link text-white "
                    aria-controls="pagesExamples" role="button" aria-expanded="false">
                    <i class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">image</i>
                    <span class="nav-link-text ms-2 ps-1">Pages</span>
                </a>
                <div class="collapse " id="pagesExamples">
                    <ul class="nav ">
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#profileExample">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Profile <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="profileExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/profile/overview.html">
                                            <span class="sidenav-mini-icon"> P </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Profile Overview </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/profile/projects.html">
                                            <span class="sidenav-mini-icon"> A </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> All Projects </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/profile/messages.html">
                                            <span class="sidenav-mini-icon"> M </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Messages </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#usersExample">
                                <span class="sidenav-mini-icon"> U </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Users <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="usersExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/users/reports.html">
                                            <span class="sidenav-mini-icon"> R </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Reports </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/users/new-user.html">
                                            <span class="sidenav-mini-icon"> N </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> New User </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#accountExample">
                                <span class="sidenav-mini-icon"> A </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Account <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="accountExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/account/settings.html">
                                            <span class="sidenav-mini-icon"> S </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Settings </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/account/billing.html">
                                            <span class="sidenav-mini-icon"> B </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Billing </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/account/invoice.html">
                                            <span class="sidenav-mini-icon"> I </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Invoice </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/account/security.html">
                                            <span class="sidenav-mini-icon"> S </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Security </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#projectsExample">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Projects <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="projectsExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/projects/general.html">
                                            <span class="sidenav-mini-icon"> G </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> General </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/projects/timeline.html">
                                            <span class="sidenav-mini-icon"> T </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Timeline </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/projects/new-project.html">
                                            <span class="sidenav-mini-icon"> N </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> New Project </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#vrExamples">
                                <span class="sidenav-mini-icon"> V </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Virtual Reality <b
                                        class="caret"></b></span>
                            </a>
                            <div class="collapse " id="vrExamples">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/vr/vr-default.html">
                                            <span class="sidenav-mini-icon"> V </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> VR Default </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../pages/vr/vr-info.html">
                                            <span class="sidenav-mini-icon"> V </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> VR Info </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../pages/pricing-page.html">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Pricing Page </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../pages/rtl-page.html">
                                <span class="sidenav-mini-icon"> R </span>
                                <span class="sidenav-normal  ms-2  ps-1"> RTL </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../pages/widgets.html">
                                <span class="sidenav-mini-icon"> W </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Widgets </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../pages/charts.html">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Charts </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../pages/sweet-alerts.html">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Sweet Alerts </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../pages/notifications.html">
                                <span class="sidenav-mini-icon"> N </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Notifications </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#applicationsExamples" class="nav-link text-white "
                    aria-controls="applicationsExamples" role="button" aria-expanded="false">
                    <i class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">apps</i>
                    <span class="nav-link-text ms-2 ps-1">Applications</span>
                </a>
                <div class="collapse " id="applicationsExamples">
                    <ul class="nav ">
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../applications/crm.html">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> CRM </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../applications/kanban.html">
                                <span class="sidenav-mini-icon"> K </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Kanban </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../applications/wizard.html">
                                <span class="sidenav-mini-icon"> W </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Wizard </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../applications/datatables.html">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal  ms-2  ps-1"> DataTables </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../applications/calendar.html">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Calendar </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../applications/stats.html">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Stats </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../applications/validation.html">
                                <span class="sidenav-mini-icon"> V </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Validation </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#ecommerceExamples" class="nav-link text-white "
                    aria-controls="ecommerceExamples" role="button" aria-expanded="false">
                    <i
                        class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">shopping_basket</i>
                    <span class="nav-link-text ms-2 ps-1">Ecommerce</span>
                </a>
                <div class="collapse " id="ecommerceExamples">
                    <ul class="nav ">
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#productsExample">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Products <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="productsExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../ecommerce/products/new-product.html">
                                            <span class="sidenav-mini-icon"> N </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> New Product </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../ecommerce/products/edit-product.html">
                                            <span class="sidenav-mini-icon"> E </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Edit Product </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../ecommerce/products/product-page.html">
                                            <span class="sidenav-mini-icon"> P </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Product Page </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../ecommerce/products/products-list.html">
                                            <span class="sidenav-mini-icon"> P </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Products List </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#ordersExample">
                                <span class="sidenav-mini-icon"> O </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Orders <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="ordersExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../ecommerce/orders/list.html">
                                            <span class="sidenav-mini-icon"> O </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Order List </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../ecommerce/orders/details.html">
                                            <span class="sidenav-mini-icon"> O </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Order Details </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " href="../ecommerce/referral.html">
                                <span class="sidenav-mini-icon"> R </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Referral </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#authExamples" class="nav-link text-white "
                    aria-controls="authExamples" role="button" aria-expanded="false">
                    <i
                        class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">content_paste</i>
                    <span class="nav-link-text ms-2 ps-1">Authentication</span>
                </a>
                <div class="collapse " id="authExamples">
                    <ul class="nav ">
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#signinExample">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Sign In <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="signinExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/signin/basic.html">
                                            <span class="sidenav-mini-icon"> B </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Basic </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/signin/cover.html">
                                            <span class="sidenav-mini-icon"> C </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Cover </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../authentication/signin/illustration.html">
                                            <span class="sidenav-mini-icon"> I </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Illustration </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#signupExample">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Sign Up <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="signupExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/signup/basic.html">
                                            <span class="sidenav-mini-icon"> B </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Basic </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/signup/cover.html">
                                            <span class="sidenav-mini-icon"> C </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Cover </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../authentication/signup/illustration.html">
                                            <span class="sidenav-mini-icon"> I </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Illustration </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#resetExample">
                                <span class="sidenav-mini-icon"> R </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Reset Password <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="resetExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/reset/basic.html">
                                            <span class="sidenav-mini-icon"> B </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Basic </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/reset/cover.html">
                                            <span class="sidenav-mini-icon"> C </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Cover </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../authentication/reset/illustration.html">
                                            <span class="sidenav-mini-icon"> I </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Illustration </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#lockExample">
                                <span class="sidenav-mini-icon"> L </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Lock <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="lockExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/lock/basic.html">
                                            <span class="sidenav-mini-icon"> B </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Basic </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/lock/cover.html">
                                            <span class="sidenav-mini-icon"> C </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Cover </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../authentication/lock/illustration.html">
                                            <span class="sidenav-mini-icon"> I </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Illustration </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#StepExample">
                                <span class="sidenav-mini-icon"> 2 </span>
                                <span class="sidenav-normal  ms-2  ps-1"> 2-Step Verification <b
                                        class="caret"></b></span>
                            </a>
                            <div class="collapse " id="StepExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../authentication/verification/basic.html">
                                            <span class="sidenav-mini-icon"> B </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Basic </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../authentication/verification/cover.html">
                                            <span class="sidenav-mini-icon"> C </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Cover </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../authentication/verification/illustration.html">
                                            <span class="sidenav-mini-icon"> I </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Illustration </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#errorExample">
                                <span class="sidenav-mini-icon"> E </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Error <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="errorExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/error/404.html">
                                            <span class="sidenav-mini-icon"> E </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Error 404 </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="../authentication/error/500.html">
                                            <span class="sidenav-mini-icon"> E </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Error 500 </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#basicExamples" class="nav-link text-white "
                    aria-controls="basicExamples" role="button" aria-expanded="false">
                    <i
                        class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">upcoming</i>
                    <span class="nav-link-text ms-2 ps-1">Basic</span>
                </a>
                <div class="collapse " id="basicExamples">
                    <ul class="nav ">
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#gettingStartedExample">
                                <span class="sidenav-mini-icon"> G </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Getting Started <b
                                        class="caret"></b></span>
                            </a>
                            <div class="collapse " id="gettingStartedExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../../../../www.creative-tim.com/learning-lab/bootstrap/quick-start/material-dashboard.html"
                                            target="_blank">
                                            <span class="sidenav-mini-icon"> Q </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Quick Start </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../../../../www.creative-tim.com/learning-lab/bootstrap/license/material-dashboard.html"
                                            target="_blank">
                                            <span class="sidenav-mini-icon"> L </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> License </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../../../../www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard.html"
                                            target="_blank">
                                            <span class="sidenav-mini-icon"> C </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Contents </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../../../../www.creative-tim.com/learning-lab/bootstrap/build-tools/material-dashboard.html"
                                            target="_blank">
                                            <span class="sidenav-mini-icon"> B </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Build Tools </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white " data-bs-toggle="collapse" aria-expanded="false"
                                href="#foundationExample">
                                <span class="sidenav-mini-icon"> F </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Foundation <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="foundationExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../../../../www.creative-tim.com/learning-lab/bootstrap/colors/material-dashboard.html"
                                            target="_blank">
                                            <span class="sidenav-mini-icon"> C </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Colors </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../../../../www.creative-tim.com/learning-lab/bootstrap/grid/material-dashboard.html"
                                            target="_blank">
                                            <span class="sidenav-mini-icon"> G </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Grid </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../../../../www.creative-tim.com/learning-lab/bootstrap/typography/material-dashboard.html"
                                            target="_blank">
                                            <span class="sidenav-mini-icon"> T </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Typography </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white "
                                            href="../../../../www.creative-tim.com/learning-lab/bootstrap/icons/material-dashboard.html"
                                            target="_blank">
                                            <span class="sidenav-mini-icon"> I </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Icons </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#componentsExamples" class="nav-link text-white "
                    aria-controls="componentsExamples" role="button" aria-expanded="false">
                    <i
                        class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">view_in_ar</i>
                    <span class="nav-link-text ms-2 ps-1">Components</span>
                </a>
                <div class="collapse " id="componentsExamples">
                    <ul class="nav ">
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/alerts/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> A </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Alerts </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/badge/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> B </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Badge </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/buttons/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> B </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Buttons </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/cards/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Card </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/carousel/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Carousel </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/collapse/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Collapse </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/dropdowns/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Dropdowns </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/forms/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> F </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Forms </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/modal/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> M </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Modal </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/navs/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> N </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Navs </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/navbar/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> N </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Navbar </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/pagination/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Pagination </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/popovers/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Popovers </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/progress/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Progress </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/spinners/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Spinners </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/tables/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> T </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Tables </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white "
                                href="../../../../www.creative-tim.com/learning-lab/bootstrap/tooltips/material-dashboard.html"
                                target="_blank">
                                <span class="sidenav-mini-icon"> T </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Tooltips </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                    href="../../../../github.com/creativetimofficial/ct-material-dashboard-pro/blob/master/CHANGELOG.html"
                    target="_blank">
                    <i
                        class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">receipt_long</i>
                    <span class="nav-link-text ms-2 ps-1">Changelog</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
