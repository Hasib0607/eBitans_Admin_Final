@php
    $modulus_type = $modulus_type ?? 0;
    $userData = getUserData();
    $store_id = $userData['store_id'];
    $active_addons = \App\Models\Modulus::with(['getModulus'])->where("status", 1)
                ->whereHas('getModulus', function ($query) use ($store_id) {
                    $query->where('store_id', $store_id)
                        ->where('status', 1);
                })
                ->where("status", 1)
                ->where("modulus_type", $modulus_type)
                ->where("config_status", 1)
                ->get();
@endphp
@if(isset($active_addons) && count($active_addons) > 0)
    @foreach($active_addons as $item)
        <li class="breadcrumb-item @if(request()->routeIs('admin.modulus.config', ['id' => $item->id]) && request()->route('id') == $item->id) active @endif">
            <a href="{{ route('admin.modulus.config', ['id' => $item->id]) }}">
                <img src="{{ URL::to('/') }}/img/icons/resume.png" style="margin-bottom: 10px;"
                     alt=""/> <br>
                <span
                    class="nav-link-text ms-1">
                                        {{ $item->name ?? "" }}
                                    </span>
            </a>
        </li>
    @endforeach
@endif

