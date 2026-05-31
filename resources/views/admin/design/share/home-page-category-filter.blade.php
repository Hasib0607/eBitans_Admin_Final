<div class="col-md-6">
    <form action="{{ route('admin.design.category.filter') }}"
          method="get">
        <input type="hidden" name="current_page" value={{$current_page}}>
        <input type="hidden" name="column" value={{$column}}>
        <select class="form-control" name="categoryfilter" id="category"
                onchange="this.form.submit()">
            <option value="all"
                    @if (isset($stts) && $stts == 'all') selected @endif>All
                Design
                Category
            </option>
            <option value="Fashion"
                    @if (isset($stts) && $stts == 'Fashion') selected @endif>
                Fashion
            </option>
            <option value="Grocery"
                    @if (isset($stts) && $stts == 'Grocery') selected @endif>
                Grocery
            </option>
            <option value="Medical Equipment"
                    @if (isset($stts) && $stts == 'Medical Equipment') selected @endif>
                Medical Equipment
            </option>
            <option value="Furniture"
                    @if (isset($stts) && $stts == 'Furniture') selected @endif>
                Furniture
            </option>
            <option value="Gadget"
                    @if (isset($stts) && $stts == 'Gadget') selected @endif>
                Gadget
            </option>
            <option value="Gym & Sports"
                    @if (isset($stts) && $stts == 'Gym & Sports') selected @endif>
                Gym & Sports
            </option>
            <option value="Pet Animals"
                    @if (isset($stts) && $stts == 'Pet Animals') selected @endif>
                Pet Animals
            </option>
            <option value="Seasonal"
                    @if (isset($stts) && $stts == 'Seasonal') selected @endif>
                Seasonal
            </option>
            <option value="Electronics"
                    @if (isset($stts) && $stts == 'Electronics') selected @endif>
                Electronics
            </option>
            <option value="Gift"
                    @if (isset($stts) && $stts == 'Gift') selected @endif>Gift
            </option>
            <option value="Flowers"
                    @if (isset($stts) && $stts == 'Flowers') selected @endif>
                Flowers
            </option>
            <option value="Books"
                    @if (isset($stts) && $stts == 'Books') selected @endif>Books
            </option>
            <option value="Vehicle"
                    @if (isset($stts) && $stts == 'Vehicle') selected @endif>
                Vehicle
            </option>
        </select>
    </form>
</div>
