 <!-- Modal -->
 <div class="modal fade"
 id="AnalyticesCommentModal" tabindex="-1"
 aria-labelledby="exampleModalLabelClient" aria-hidden="true">
 <div class="modal-dialog modal-lg">
     <div class="modal-content" style="text-align: left;">
         <div class="modal-header"
             style="background-color: black; color:wheat;padding: 6px 25px 0px 25px;">
             <h5 class="modal-title"
                 id="exampleModalLabelClient">
                 <a href="http://{{ $store->url ?? 'Unauthorized' }}"
                     target="_blank">
                     {{ $store->name ?? 'Unauthorized' }}
                 </a>
                 <span style="font-size: 14px;">
                     ({{ $store->getUser->name ?? 'Name not found' }}
                     -{{ $store->getUser->id ?? '' }})
                 </span>

                 <p>{{$store->getUser->phone ?? 'Phone not found' }}
                 </p>
             </h5>

             <button type="button" class="btn-close"
                 data-bs-dismiss="modal"
                 aria-label="Close"></button>
         </div>


         <div class="modal-body">
             @if (!empty($comments))
                 <form id="clientComment" onsubmit="event.preventDefault();"
                     class="p-3"
                     style="border: 1px dashed crimson;"
                     action="{{ route('superadmin.clients.activities.comments') }}"
                     method="POST">
                     @csrf

                     <div class="col-12">
                         <input type="hidden" name="user_id"
                             value="{{ $store->getUser->id ?? 'empty' }}">
                         <input type="hidden" name="store_id"
                             value="{{ $store->id ?? null }}">
                     </div>

                     <div class="row">
                         <div class="col-md-12">
                             <label
                                 for="clientStatus">
                                 Client Status </label>
                             <select name="clientStatus"
                                 id="clientStatus" class="form-control" required>
                                 <option selected disabled>Select
                                     Client Status
                                 </option>
                                 <option value="Positive">
                                     Positive</option>
                                 <option value="thinking">
                                     Thinking</option>
                                 <option value="No Response">No
                                     Response</option>
                                 <option value="Interested">
                                     Interested</option>
                                 <option value="Maybe">Maybe
                                 </option>
                                 <option value="No interested">
                                     Not Interested</option>
                                 <option value="Freelancer">
                                     Freelancer</option>
                                 <option value="tut">Tut tut
                                 </option>
                             </select>
                         </div>

                         <div class="col-md-6 mt-4">
                             <label for="followUpData">Follow-Up
                                 Data</label>
                             <input type="date"
                                 class="form-control"
                                 name="followUpData"
                                 id="followUpData" required>
                         </div>
                         <div class="col-md-6 mt-4">
                             <label for="followUpTime">Follow-Up
                                 Time</label>
                             <input type="time"
                                 class="form-control"
                                 name="followUpTime"
                                 id="followUpTime">
                         </div>

                         <div class="col-md-12 mt-4">
                             <label for="comment"> Comments
                             </label>
                             <textarea name="comment" class="form-control" id="comment" required cols="30" rows="5"></textarea>
                         </div>

                         <div class="col-12">
                             <input type="hidden"
                                 id="contVal"
                                 name=""
                                 value="{{ $comments->count() }}">
                             <button type="button"
                                 style="border: 1px dashed;"
                                 class="btn btn-default mt-3">Total
                                 Follow-Up:
                                 <span
                                     id="conut">{{ $comments->count() }}</span>
                             </button>

                             <button type="submit"
                                 id="submitBtn"
                                 onclick="SubMitFrom({{ $store->getUser->id }})"
                                 style="float: right;"
                                 class="btn btn-info mt-3">Comment</button>

                         </div>
                     </div>
                 </form>
             @else
                 <h2>There is no Comments here yet</h2>
             @endif

             <div id="resCmt"
                 class="row px-3">
                 @foreach ($comments as $item)
                     <div class="col-md-12 mt-3"
                         style="border: 1px dashed lightseagreen;padding: 10px">
                         <h4 class="">
                             {{ $item->short_comment }}
                             <span style="float: right;font-size: 16px;font-weight: 500;">
                                 {{ date('d-m-Y, h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}
                                 <br>
                                 <small style="float: right;"><strong>-- {{ $item->comment_by?? 'Kabir' }}</strong></small>
                             </span>
                         </h4>
                         <p class="m-0">
                             <strong>Next Follow Up:</strong>
                             <br>
                             {{ date('d-m-Y', strtotime($item->follow_up_date ?? '2000-01-01')) }},
                             {{ date('h:i:s A', strtotime($item->follow_up_time ?? '10:00:00')) }}
                         </p>
                         <p class="m-0">
                             <strong>Comment:</strong> <br>
                             {{ $item->comment }}
                         </p>
                     </div>
                 @endforeach
             </div>

         </div>
         <div class="modal-footer">
             <button type="button" class="btn btn-secondary"
                 data-bs-dismiss="modal">Close</button>
         </div>
     </div>
 </div>
</div>
<!----Modal End---->
