<?php

    namespace App\Http\Controllers\SuperAdmin;

    use App\Http\Controllers\Controller;
    use App\Models\Currency;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Validator;

    class CurrencyController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function index()
        {
            $urls = "settings";

            $currencies = Currency::all();

            return view('superadmin.currency.index', compact('currencies', 'urls'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return \Illuminate\Http\RedirectResponse
         * @throws \Illuminate\Validation\ValidationException
         */
        public function store(Request $request)
        {
            $request->merge(['status' => $request->has('status') ? $request->status == "on" ?? 0 : 0]);
            $request->merge(['customize_rate_status' => $request->has('customize_rate_status') ? $request->customize_rate_status == "on" ?? 0 : 0]);
            $validator = Validator::make($request->all(), [
                'country' => 'required|string|max:100',
                'code' => 'required|string|max:10',
                'symbol' => 'required|string|max:1',
                'customize_rate_status' => 'required|boolean',
                'status' => 'required|boolean'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $currency = Currency::create($validator->validated());
            if ($currency) {
                Session::flash('success', 'Currency successfully created.');
            } else {
                Session::flash('error', 'Currency creation failed.');
            }
            return redirect()->route('super_admin.settings.currency_list');
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param $id
         * @return \Illuminate\Http\RedirectResponse
         * @throws \Illuminate\Validation\ValidationException
         */
        public function update(Request $request, $id)
        {
            $request->merge(['status' => $request->has('status') ? $request->status == "on" ?? 0 : 0]);
            $request->merge(['customize_rate_status' => $request->has('customize_rate_status') ? $request->customize_rate_status == "on" ?? 0 : 0]);
            $validator = Validator::make($request->all(), [
                'country' => 'required|string|max:100',
                'code' => 'required|string|max:10',
                'symbol' => 'required|string|max:1',
                'customize_rate_status' => 'required|boolean',
                'status' => 'required|boolean'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $currency = Currency::findOrfail($id);
            if (!isset($currency)) {
                Session::flash('error', 'Currency not found.');
            }
            $currency = $currency->update($validator->validated());
            if ($currency) {
                Session::flash('success', 'Currency successfully Updated.');
            } else {
                Session::flash('error', 'Currency update failed.');
            }
            return redirect()->route('super_admin.settings.currency_list');
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\RedirectResponse|void
         * @throws \Illuminate\Validation\ValidationException
         */
        public function status_change(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric|exists:currencies,id',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $currency = Currency::findOrFail($validator->validated()['id']);
            if (!isset($currency)) {
                Session::flash('error', 'Currency not found.');
            } else {
                $currency->status = !$currency->status;
                if ($currency->save()) {
                    Session::flash('success', 'Currency successfully Change Status.');
                } else {
                    Session::flash('error', 'Currency Change Status failed.');
                }
            }
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\RedirectResponse|void
         * @throws \Illuminate\Validation\ValidationException
         */
        public function status_rate_change(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric|exists:currencies,id',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $currency = Currency::findOrFail($validator->validated()['id']);
            if (!isset($currency)) {
                Session::flash('error', 'Currency not found.');
            } else {
                $currency->customize_rate_status = !$currency->customize_rate_status;
                if ($currency->save()) {
                    Session::flash('success', 'Currency successfully Change Status.');
                } else {
                    Session::flash('error', 'Currency Change Status failed.');
                }
            }
        }
    }
