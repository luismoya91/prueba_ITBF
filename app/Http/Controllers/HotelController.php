<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use Illuminate\Http\Request;
use App\Http\Resources\HotelResource;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return HotelResource::collection(hotel::latest()->with('rooms')->paginate());
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
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'doc_number' => 'required',
            'total_rooms' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if(empty($validator->errors()->all())){
            $hotel_temp = hotel::where('name', $validator->validated()['name'])
                                ->where('doc_number', $validator->validated()['doc_number'])->get();
            if($hotel_temp->count() === 0){
                $hotel = new hotel();
                $hotel->name = $validator->validated()['name'];
                $hotel->address = $validator->validated()['address'];
                $hotel->city = $validator->validated()['city'];
                $hotel->doc_number = $validator->validated()['doc_number'];
                $hotel->total_rooms = $validator->validated()['total_rooms'];
                $hotel->save();
                return response()->json([
                    'error' => false,
                    'hotel' => $hotel->latest()->with('rooms')->get()
                ]);

            }else{
                return response()->json([
                    'error' => true,
                    'errors' => ['The hotel already exist']]);
            }
        }else{
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()->all()]);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function show(hotel $hotel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit(hotel $hotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, hotel $hotel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(hotel $hotel)
    {
        //
    }
}
