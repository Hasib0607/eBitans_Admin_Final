<div class="col-12">
    <div class="alert alert-info pt-2 pb-3" style="background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);"
        role="alert">
        <span style="color:#fff">
            All PSE Visitors
        </span>
        <ul style="display: unset;">
            <li style="padding:0px;border:0px;">
                <a href="{{ route('all.visitor') }}" class="btn btn-primary btn-sm"
                    style="display:block;border-radius:0px !important">
                    All Visitor
                </a>
            </li>
            <li style="padding:0px;border:0px;">
                <a href="{{ route('monthly.report') }}" style="display:block;border-radius:0px !important"
                    class="btn btn-info btn-sm">
                    Monthly
                </a>
            </li>
            <li style="padding:0px;border:0px;">
                <a href="{{ route('weekly.report') }}" style="display:block;border-radius:0px !important"
                    class="btn btn-secondary btn-sm">
                    Weekly
                </a>
            </li>
            <li style="padding:0px;border:0px;">
                <a href="{{ route('product.khujo') }}" class="btn btn-primary btn-sm"
                    style="display:block;border-radius:0px !important">
                    Daily
                </a>
            </li>
        </ul>
    </div>
</div>
