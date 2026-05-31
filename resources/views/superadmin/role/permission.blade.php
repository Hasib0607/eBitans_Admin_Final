@extends('admin.layouts.main')
@section('content')
    <style>
        .productlist .card-body .table td {
            text-align: left !important;
        }
    </style>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.share.staff-role-permission-nav.nav')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>Add Permission</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a href="javascript:void(0)">Create New</a></li>
                        <li><a href="">Import</a></li>
                        <li><a href="">Export</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Permission</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.savepermission', $role->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 row">
                                    <table class="table" width="10%">
                                        <?php
                                        $permission = explode(',', $role->permission);
                                        foreach ($permission as $key => $pr) {
                                            if ($pr == 'branch_delete_request') {
                                                $branch_delete_request = 1;
                                            } elseif ($pr == 'customer') {
                                                $customer = 1;
                                            } elseif ($pr == 'domain') {
                                                $domain = 1;
                                            } elseif ($pr == 'domain_request') {
                                                $domain_request = 1;
                                            } elseif ($pr == 'design') {
                                                $design = 1;
                                            } elseif ($pr == 'template') {
                                                $template = 1;
                                            } elseif ($pr == 'affiliate') {
                                                $affiliate = 1;
                                            } elseif ($pr == 'order') {
                                                $order = 1;
                                            } elseif ($pr == 'staff') {
                                                $staff = 1;
                                            } elseif ($pr == 'role_and_permission') {
                                                $role_and_permission = 1;
                                            } elseif ($pr == 'clients') {
                                                $clients = 1;
                                            } elseif ($pr == 'paid_clients') {
                                                $paid_clients = 1;
                                            } elseif ($pr == 'clients_Activities') {
                                                $clients_Activities = 1;
                                            } elseif ($pr == 'clients_Follow_Up') {
                                                $clients_Follow_Up = 1;
                                            } elseif ($pr == 'plan_order') {
                                                $plan_order = 1;
                                            } elseif ($pr == 'plans') {
                                                $plans = 1;
                                            } elseif ($pr == 'smm') {
                                                $smm = 1;
                                            } elseif ($pr == 'blog') {
                                                $blog = 1;
                                            } elseif ($pr == 'webSetup') {
                                                $webSetup = 1;
                                            } elseif ($pr == 'notification') {
                                                $notification = 1;
                                            } elseif ($pr == 'message') {
                                                $message = 1;
                                            } elseif ($pr == 'chatbot') {
                                                $chatbot = 1;
                                            } elseif ($pr == 'chat_assign') {
                                                $chat_assign = 1;
                                            } elseif ($pr == 'whatsapp') {
                                                $whatsapp = 1;
                                            } elseif ($pr == 'pse') {
                                                $pse = 1;
                                            } elseif ($pr == 'landing_page_clients') {
                                                $landingPageClient = 1;
                                            } elseif ($pr == 'paid_clients_list') {
                                                $paidClientsList = 1;
                                            } else {
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="branch_delete_request"
                                                                  @if (isset($branch_delete_request) && $branch_delete_request == '1') checked @endif>
                                            </th>
                                            <td width="16%">Branch Delete Request</td>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="customer"
                                                                  @if (isset($customer) && $customer == '1') checked @endif>
                                            </th>
                                            <td width="15%">Customer</td>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="domain"
                                                                  @if (isset($domain) && $domain == '1') checked @endif>
                                            </th>
                                            <td width="15%">Domain</td>
                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="domain_request"
                                                                  @if (isset($domain_request) && $domain_request == '1') checked @endif>
                                            </th>
                                            <td width="15%">Domain Request</td>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="design"
                                                                  @if (isset($design) && $design == '1') checked @endif>
                                            </th>
                                            <td width="15%">Design</td>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="template"
                                                                  @if (isset($template) && $template == '1') checked @endif>
                                            </th>
                                            <td width="20%">Template</td>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="affiliate"
                                                                  @if (isset($affiliate) && $affiliate == '1') checked @endif>
                                            </th>
                                            <td width="20%">Affiliate</td>
                                        </tr>
                                        <tr>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="order"
                                                                  @if (isset($order) && $order == '1') checked @endif>
                                            </th>
                                            <td width="16%">Order</td>

                                            <th width="1%"><input type="checkbox" name="permission[]" value="staff"
                                                                  @if (isset($staff) && $staff == '1') checked @endif>
                                            </th>
                                            <td width="15%">Staff</td>

                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="role_and_permission"
                                                                  @if (isset($role_and_permission) && $role_and_permission == '1') checked @endif>
                                            </th>

                                            <td width="15%">Role and Permission</td>


                                            <th width="1%"><input type="checkbox" name="permission[]" value="clients"
                                                                  @if (isset($clients) && $clients == '1') checked @endif>
                                            </th>
                                            <td width="20%">Clients</td>

                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="paid_clients"
                                                                  @if (isset($paid_clients) && $paid_clients == '1') checked @endif>
                                            </th>
                                            <td width="20%">Paid Clients</td>


                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="clients_Activities"
                                                                  @if (isset($clients_Activities) && $clients_Activities == '1') checked @endif>
                                            </th>
                                            <td width="15%">clients Activities</td>


                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="clients_Follow_Up"
                                                                  @if (isset($clients_Follow_Up) && $clients_Follow_Up == '1') checked @endif>
                                            </th>
                                            <td width="15%">clients Follow Up</td>

                                        </tr>
                                        <tr>
                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="notification"
                                                                  @if (isset($notification) && $notification == '1') checked @endif>
                                            </th>
                                            <td width="15%">Notification</td>

                                            <th width="1%">
                                                <input type="checkbox" name="permission[]" value="message"
                                                       @if (isset($message) && $message == '1') checked @endif>
                                            </th>
                                            <td width="15%">Message</td>

                                            <th width="1%">
                                                <input type="checkbox" name="permission[]" value="chatbot"
                                                       @if (isset($chatbot) && $chatbot == '1') checked @endif>
                                            </th>
                                            <td width="15%">Chat Bot</td>

                                            <th width="1%">
                                                <input type="checkbox" name="permission[]" value="chat_assign"
                                                       @if (isset($chat_assign) && $chat_assign == '1') checked @endif>
                                            </th>
                                            <td width="15%">Chat Agent Assign</td>

                                            <th width="1%">
                                                <input type="checkbox" name="permission[]" value="whatsapp"
                                                       @if (isset($whatsapp) && $whatsapp == '1') checked @endif>
                                            </th>
                                            <td width="15%">WhatsApp</td>

                                            <th width="1%"><input type="checkbox" name="permission[]" value="plan_order"
                                                                  @if (isset($plan_order) && $plan_order == '1') checked @endif>
                                            </th>
                                            <td width="16%">Plan Order</td>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="plans"
                                                                  @if (isset($plans) && $plans == '1') checked @endif>
                                            </th>
                                            <td width="15%">Plans</td>

                                            <th width="1%"><input type="checkbox" name="permission[]" value="smm"
                                                                  @if (isset($smm) && $smm == '1') checked @endif></th>
                                            <td width="15%"> Social Media Marketing</td>

                                        </tr>
                                        <tr>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="blog"
                                                                  @if (isset($blog) && $blog == '1') checked @endif>
                                            </th>
                                            <td width="15%"> Blog</td>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="webSetup"
                                                                  @if (isset($webSetup) && $webSetup == '1') checked @endif>
                                            </th>
                                            <td width="15%"> Web Site Setup</td>
                                            <th width="1%"><input type="checkbox" name="permission[]" value="pse"
                                                                  @if (isset($pse) && $pse == '1') checked @endif>
                                            </th>
                                            <td width="15%"> PSE</td>
                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="landing_page_clients"
                                                                  @if (isset($landingPageClient) && $landingPageClient == '1') checked @endif>
                                            </th>
                                            <td width="15%">Landing Page Client</td>
                                            <th width="1%"><input type="checkbox" name="permission[]"
                                                                  value="paid_clients_list"
                                                                  @if (isset($paidClientsList) && $paidClientsList == '1') checked @endif>
                                            </th>
                                            <td width="15%">Paid Client List</td>
                                            
                                        </tr>

                                    </table>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8" style="text-align:right">
                                        <button type="submit" class="btn btn-info">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function () {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });
    </script>
@endpush
