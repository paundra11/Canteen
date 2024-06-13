<?php

namespace App\Http\Controllers;

use App\Models\Canteen;
use App\Models\Order;
use App\Models\Rfid;
use App\Models\User;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CanteenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Canteen";
        if (is_null(auth()->user()->canteen)) {
            if (is_null(auth()->user()->wallet)) {
                return view('panel.wallet.setup');
            }
            return view('panel.canteen.setup');
        } else {
            $food = auth()->user()->canteen->food;
            return view('panel.canteen.index', compact(['title', 'food']));
        }
    }
    public function detail(Order $order, Canteen $canteen)
    {
        $canteen = Canteen::find($canteen->id);

        $list = [];
        $list1 = explode(',', $order->food_list);
        unset($list1[0]);
        $count = array_count_values($list1);
        $list1 = array_unique($list1);
        foreach ($list1 as $ls) {
            array_push($list, Food::find($ls));
        }
        return view('panel.canteen.detail', compact('order', 'list', 'count'));
    }
    public function cancel(Order $order)
    {
        $order->delete();
        return redirect()->route('panel.canteen');
    }

    public function beli(Request $request)
    {
        // dd($request->all());
        $canteen = Canteen::find($request->canteen_id)->user->wallet;
        $total = (int)$request->total;
        $request->validate([
            'food_list' => 'required',
            'total' => 'required',
            'description' => 'required',
        ]);
        $food_list = explode(",", $request['food_list']);
        unset($food_list[0]);
        foreach ($food_list as $value) {
            $food = Food::find($value);
            $food->stock = $food->stock - 1;
            $food->save();
        }

        $wallet = auth()->user()->wallet;
        $canteen->balance = strval(($canteen->balance) + $total);
        $wallet->balance = strval(($wallet->balance) - $total);
        $canteen->save();
        $wallet->save();

        // Create a new cashout record
        \App\Models\Cashout::create([
            'user_id' => auth()->user()->id,
            'amount' => $total,
            'description' => 'Purchase from canteen',
        ]);

        $req = $request->all();
        $req['user_id'] = auth()->user()->id;
        Order::create($req);
        return redirect()->route('panel.canteen.explore');
    }

    public function order()
    {
        return Order::join('users', 'orders.user_id', '=', 'users.id')->join('wallets', 'orders.user_id', '=', 'wallets.user_id')->select('orders.*', 'users.full_name', 'wallets.address', 'wallets.user_id', 'wallets.balance')->get();
        return Order::all();
    }

    public function spesific($canteen_id)
    {
        return Order::join('users', 'orders.user_id', '=', 'users.id')
            ->join('wallets', 'orders.user_id', '=', 'wallets.user_id')
            ->join('canteens', 'orders.canteen_id', '=', 'canteens.id')
            ->select('orders.*', 'users.full_name', 'wallets.address', 'wallets.user_id', 'wallets.balance')
            ->where('canteens.id', $canteen_id)
            ->get();
    }

    public function toggleStatus(Request $request)
    {
        // dd($request->all());

        $userCanteenId = Auth::user()->canteen->id;

        $canteen = Canteen::find($userCanteenId);
        
        if (!$canteen) {
            return redirect()->route('panel.canteen')->with('error', 'Canteen not found.');
        }
        
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);
        
        $canteen->status = $request->status;
        $canteen->save();
        
        return redirect()->route('panel.canteen')->with('success', 'Canteen status updated successfully.');
 
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setup(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'name' => 'required',
        ]);
        $image = $request->file("image");
        $image = $image->store("public/image/canteen");
        $canteen = $request->all();
        $canteen['image'] = str_replace('public/image/', "image/", $image);
        Auth()->user()->canteen()->create($canteen);
        return redirect()->route('panel.canteen');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi input
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'jenis' => 'required',
            'canteen_id' => 'required',
        ]);

        try {
            // Menyimpan gambar
            $image = $request->file('image');
            $imagePath = $image->store('public/image/canteen/food');
            $imagePath = str_replace('public/image/', "image/", $imagePath);

            // Mengambil data request dan menambahkan path gambar
            $foodData = $request->all();
            $foodData['image'] = $imagePath;

            // Menggunakan Auth untuk mendapatkan user yang sedang login
            $user = Auth::user();

            // Membuat entitas Food baru menggunakan relasi canteen dari user yang login
            $food = $user->canteen->food()->create($foodData);

            return redirect()->back()->with('success', 'Food created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an error creating the food: ' . $e->getMessage());
        }
    }

    public function pay($rfid)
    {
        $rfid2 = Rfid::where('rfid', $rfid)->first();
        if (!$rfid2) {
            return "  Card invalid";
        }
        $rrfid = $rfid2->wallet->user->myorders->last();
        if (!is_null($rrfid)) {
            if ($rrfid->payment == 1) {
                return "     No tax";
            }
        } else {
            return "     No tax";
        }
        $rrfid->payment = 1;
        $rrfid->save();
        #        return $rfid2->wallet->user->full_name;
        return "    Success";
    }
    public function explore()
    {
        $title = "Explore Canteen";
        $canteen = Canteen::all();
        return view('panel.canteen.explore', compact(['canteen', 'title']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Canteen  $canteen
     * @return \Illuminate\Http\Response
     */
    public function show(Canteen $canteen)
    {
        $title = "Explore $canteen->name";
        $food = $canteen->food->all();
        // dd($food);
        return view('panel.canteen.show', compact(['food', 'title']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Canteen  $canteen
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $userId)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Canteen  $canteen
     * @return \Illuminate\Http\Response
     */
    // CanteenController.php
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'jenis' => 'required',
        ]);

        $food = Food::find($request->food_id);

        if ($request->hasFile('image')) {
            $image = $request->file("image");
            $image = $image->store("public/image/canteen/food");
            $food->image = str_replace('public/image/', "image/", $image);
        }

        $food->name = $request->name;
        $food->price = $request->price;
        $food->stock = $request->stock;
        $food->jenis = $request->jenis;

        $food->save();

        return redirect()->route('panel.canteen');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Canteen  $canteen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $food = Food::findOrFail($id);
        
        // Hapus gambar dari storage jika ada
        if (\Storage::exists('public/' . $food->image)) {
            \Storage::delete('public/' . $food->image);
        }

        // Hapus item dari database
        $food->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('panel.canteen')->with('success', 'Menu berhasil dihapus');
    }
}
