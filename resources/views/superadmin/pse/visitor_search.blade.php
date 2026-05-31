<table class="table table-striped" width="100%" id="taskfilterresult">
    <thead>
        <tr>
            <th width="4%">SL NO</th>
            <th width="5%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    ছবি
                @else
                    Image
                @endif
            </th>
            <th width="55%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    নাম
                @else
                    Product Name
                @endif
            </th>
            <th width="5%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    দোকানের নাম
                @else
                    Store Name
                @endif
            </th>
            <th width="5%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    মোট ভিজিটর
                @else
                    Total Visitor
                @endif
            </th>
            <th width="11%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    এডিট/ডিলিট
                @else
                    Action
                @endif
            </th>
        </tr>
    </thead>
    <tbody>
        @if (!is_null($visitors))
            @php
                $totalItems = $visitors->total(); // Get the total count of items
                $perPage = $visitors->perPage(); // Get the number of items displayed per page
                $currentPage = $visitors->currentPage(); // Get the current page number
                $startingId = $totalItems - $perPage * ($currentPage - 1);
                $countVisitor = \App\Models\StaticVisitor::first();
                $total = $countVisitor ? $countVisitor->visitors : 0;
            @endphp
            @foreach ($visitors as $visitor)
                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                    <td>{{ $startingId-- }}</td>
                    <td>
                        <img src="{{ URL::to('/') }}/assets/images/setting/{{ $visitor->logo }}" class="zoom"
                            width="30px">
                    </td>
                    <td style="text-align: center;">
                        <p style="color:#000">{{ $visitor->name }}</p>
                    </td>
                    <td style="text-align: center;">{{ $visitor->url }}</td>
                    <td style="text-align: center;">
                        @if ($total != 0)
                            {{ $visitor->totalVisitor * $total }}
                        @else
                            {{ $visitor->totalVisitor }}
                        @endif
                    <td>
                        <a href="{{ route('superadmin.pse.visitor.details', $visitor->id) }}">
                            <img src="{{ asset('img/eye.png') }}" width="20px" height="20px">
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                <p>Visitor Not Found</p>
            </tr>
        @endif
    </tbody>
</table>
