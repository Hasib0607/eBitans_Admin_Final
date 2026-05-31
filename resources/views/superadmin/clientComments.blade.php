@foreach ($comments as $item)
    <div class="col-md-12 mt-3" style="border: 1px dashed lightseagreen;padding: 10px">
        <h4 class="">{{ $item->short_comment }} <span
                style="float: right;font-size: 16px;font-weight: 500;">
                {{ date('d-m-Y, h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}
                <br>
                <small style="float: right;"><strong>-- {{ $item->comment_by?? 'Kabir' }}</strong></small>
            </span>
        </h4>
        <p class="m-0">
            <strong>Next Follow Up:</strong> <br>
            {{ date('d-m-Y', strtotime($item->follow_up_date ?? '2000-01-01')) }},
            {{ date('h:i:s A', strtotime($item->follow_up_time ?? '10:00:00')) }}
        </p>
        <div class="m-0">
            <strong>Comment:</strong> <br>
            <textarea class="form-control" readonly>{{ $item->comment }}</textarea>
        </div>
    </div>
@endforeach
