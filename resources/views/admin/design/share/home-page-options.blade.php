<style>
    .scrollable {
        height: 63vh !important;
        /*overflow-y: scroll; !* Ensure vertical scrolling *!*/
        /*overflow-y: hidden; !* Hide vertical scrolling *!*/
        /*overflow-x: scroll; !* Enable horizontal scrolling *!*/
        /*white-space: nowrap;*/
        /*padding: 10px;*/
        /*box-sizing: border-box;*/
        /*outline: none;            !* Adjust height as needed *!*/
        overflow-y: auto;
        scroll-behavior: initial;
    }

    /* Custom Scrollbar for WebKit Browsers (e.g., Chrome, Safari) */
    .scrollable::-webkit-scrollbar {
        width: 8px; /* Vertical scrollbar width */
    }

    .scrollable::-webkit-scrollbar-track {
        background: #f1d0c9;
        border-radius: 10px;
    }

    .scrollable::-webkit-scrollbar-thumb {
        background: #dd8d7c;
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: padding-box;
    }

    .scrollable::-webkit-scrollbar-thumb:hover {
        background: #f1593a;
    }

</style>

<div class="card-body scrollable">
    <div class="form-group">
        <input type="radio" id="Homes" class="design_radio" name="{{$column}}" data-img="default-preview-design.png"
               @if (isset($is_none)) checked @endif
               value="null">&nbsp;&nbsp;&nbsp;
        <label for="Homes"> None </label> &nbsp;&nbsp;&nbsp;
    </div>
    @if (isset($design) && count($design) > 0)
        @foreach ($design as $key => $dsg)
            <div class="modal fade" id="exampleModal{{ $key }}" tabindex="-1"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content"
                         style="background-color:transparent;border:0px">

                        <div class="modal-body" style="border:none">
                            <button class="btn btn-danger sm"
                                    onclick="modalRE({{ $key }})"
                                    style="float: right; margin: 0px 8px;">X
                            </button>
                            @if (isset($dsg->image))
                                <img
                                    src="{{ URL::to('/') }}/assets/images/design/{{ $dsg->image }}"
                                    class="img-fluid" alt=""
                                    style="padding:0px 10px;border:0px solid gray;transition-delay: 5s;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <span class="design_preview w-auto" data-img="{{ $dsg->image }}" data-design="{{$dsg}}"
                      data-storedesign="{{$store_design ?? ""}}">
                    <input type="radio" id="Home{{ $key }}" name="{{$column}}"
                           class="design_radio cursor-pointer" @if (isset($dsg->design_select)) checked @endif
                           value="{{ $dsg->value }}"
                           data-img="{{ $dsg->image }}"
                           data-design="{{$dsg}}"
                    >
                    <label for="Home{{ $key }}" class="cursor-pointer">
                        {{ $dsg->name }}</label>

                </span>
                <span data-bs-toggle="modal" data-bs-target="#exampleModal{{ $key }}">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </span>
            </div>
        @endforeach
    @endif
</div>

<script>
    // Wait until the DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        const scrollContainer = document.querySelector('.scrollable');

        // Function to scroll the element vertically
        function handleScroll(event) {
            event.preventDefault(); // Prevent the default scrolling behavior
            scrollContainer.scrollTop += event.deltaY; // Scroll vertically based on mouse wheel movement
        }

        // Attach mouse wheel event listener
        scrollContainer.addEventListener('wheel', handleScroll);
    });

    $(document).ready(function () {
        $(".design_radio").on("click", function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const column = $('.design_radio').attr('name')
            $url = "/changes_design";
            var value = $(this).val();
            $.post($url, {
                value: value,
                column: column
            }, function (data) {
                sliderInfo = data;
                toastr.success('Change Design Successfully', 'Success');
            });
        });
    });
</script>
<script>
    var myModal = document.getElementById('myModal')
    var myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', function () {
        myInput.focus()
    })
</script>
<script>
    function modalRE(val) {
        $('.show').removeClass("modal-backdrop");
        $('#exampleModal' + val).toggle();
    }
</script>
