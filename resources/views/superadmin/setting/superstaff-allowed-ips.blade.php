@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.share.settings-top-nav')

        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h5 class="mb-0">IP Restriction</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('super_admin.settings.superstaff_allowed_ips.restriction') }}"
                                  method="POST">
                                @csrf
                                <div class="form-check form-switch ps-0 d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="form-check-label fw-bold" for="superstaffIpRestriction">
                                            {{ $restrictionEnabled ? 'Restriction On' : 'Restriction Off' }}
                                        </label>
                                        <p class="text-sm text-muted mb-0">
                                            {{ $restrictionEnabled ? 'Super staff can login only from allowed IPs.' : 'Super staff can login from any IP.' }}
                                        </p>
                                    </div>
                                    <input class="form-check-input ms-3" type="checkbox" name="enabled"
                                           id="superstaffIpRestriction" value="1"
                                           onchange="this.form.submit()" @if($restrictionEnabled) checked @endif>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 class="mb-0">Add Super Staff IP</h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success text-white">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger text-white">{{ session('error') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger text-white">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form action="{{ route('super_admin.settings.superstaff_allowed_ips.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                           placeholder="Hasib Office" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">IP Address</label>
                                    <input type="text" name="ip_address" class="form-control"
                                           value="{{ old('ip_address', request()->ip()) }}" placeholder="103.120.161.190"
                                           required>
                                </div>

                                <button type="submit" class="btn btn-primary mb-0">Add IP</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Allowed Super Staff IPs</h5>
                            <span class="badge bg-gradient-dark">{{ $ips->total() }} IPs</span>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive px-3">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>IP Address</th>
                                        <th>Updated</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($ips as $ip)
                                        <tr>
                                            <td>
                                                <form id="update-ip-{{ $ip->id }}"
                                                      action="{{ route('super_admin.settings.superstaff_allowed_ips.update', $ip->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name" class="form-control"
                                                           value="{{ old('name', $ip->name) }}" required>
                                                </form>
                                            </td>
                                            <td>
                                                <input type="text" name="ip_address" form="update-ip-{{ $ip->id }}"
                                                       class="form-control" value="{{ old('ip_address', $ip->ip_address) }}"
                                                       required>
                                            </td>
                                            <td class="text-sm">
                                                {{ optional($ip->updated_at)->format('d M Y, h:i A') }}
                                            </td>
                                            <td class="text-end">
                                                <button type="submit" form="update-ip-{{ $ip->id }}"
                                                        class="btn btn-sm btn-info mb-0">
                                                    Update
                                                </button>
                                                <form class="d-inline"
                                                      action="{{ route('super_admin.settings.superstaff_allowed_ips.destroy', $ip->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Delete this allowed IP?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger mb-0">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No allowed IP added yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-3 mt-3">
                                {{ $ips->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
