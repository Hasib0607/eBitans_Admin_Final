<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Store Name</th>
        <th>Id</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Total Package</th>
        <th>Total Addons</th>
        <th>Purchase Amount</th>
        <th>Comment</th>
        <th>Plan</th>
        <th>Create Date</th>
        <th>Active Date</th>
        <th>Expiry Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($paidClients as $item)
        @php
            $user = $item->getUser;
            $plan = $item->getPlan;
            $addonsOrders = $item->addonsOrders ?? collect();

            $storeTotalAddons = 0;
            $storeTotalPackage = 0;
            $purchaseAmount = $addonsOrders->sum('total') ?? 0;

            foreach ($addonsOrders as $order) {
                $addons = is_string($order->addons) ? json_decode($order->addons, true) : $order->addons;
                if (is_array($addons)) {
                    foreach ($addons as $addon) {
                        $storeTotalAddons += isset($addon['price']) ? (float) $addon['price'] : 0;
                    }
                }

                $package = is_string($order->package) ? json_decode($order->package, true) : $order->package;
                if (is_array($package) && isset($package['offerprice'])) {
                    $storeTotalPackage += (float) $package['offerprice'];
                }
            }
        @endphp
        <tr>
            <td>
                {{ $user->name ?? '' }}
                @if($item->setup_status)
                    (Setup: Buy)
                @else
                    (Setup: Not Buy)
                @endif
            </td>
            <td>{{ $item->name ?? '' }}</td>
            <td>{{ $user->id ?? '' }}</td>
            <td>{{ $user->email ?? '' }}</td>
            <td>{{ $user->phone ?? '' }}</td>
            <td>{{ number_format($storeTotalPackage, 2) }}</td>
            <td>{{ number_format($storeTotalAddons, 2) }}</td>
            <td>{{ number_format($purchaseAmount, 2) }}</td>
            <td>{{ $user->comment ?? '' }}</td>
            <td>{{ $plan->name ?? '' }}</td>
            <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M, Y') : '' }}</td>
            <td>{{ $item->purchase_date ? \Carbon\Carbon::parse($item->purchase_date)->format('d M, Y') : '' }}</td>
            <td style="color:red">{{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d M, Y') : '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
