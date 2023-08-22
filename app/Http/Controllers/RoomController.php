<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RoomResource::collection(Room::latest()->paginate());
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
            'id_hotel' => 'required',
            'body' => 'required',
            'body.*.qty' => 'required',
            'body.*.type' => 'required',
            'body.*.accommodation' => 'required'
        );
        $total_qty = 0;
        $validator = Validator::make($request->all(), $rules);
        if(empty($validator->errors()->all())){
            $validated = $validator->validated();
            foreach ($validated['body'] as $key => $room_tmp) {
                if(Room::validateAccommodationByType($room_tmp['type'], $room_tmp['accommodation'])){
                    $total_qty += $room_tmp['qty'];
                }else{
                    return response()->json([
                        'error' => true,
                        'errors' => ['The accommodation is invalid for type']]);
                }

            }

            $hotel = hotel::find($validated['id_hotel']);
            if(Room::validateHotel($hotel, $validated['body'])){
                if($hotel->count() !== 0){
                    if ($total_qty <= $hotel->total_rooms){
                        $exit_Rooms = [];
                        foreach ($validated['body'] as $key => $room_temp) {
                            $room = new Room();
                            $room->qty = $room_temp['qty'];
                            $room->type = $room_temp['type'];
                            $room->accommodation = $room_temp['accommodation'];
                            $room->hotel_id = $validated['id_hotel'];
                            $room->save();
                            $exit_Rooms[] = $room;
                        }
                        return response()->json([
                            'error' => false,
                            'room' => $exit_Rooms
                        ]);
                    }else{
                        return response()->json([
                            'error' => true,
                            'errors' => ['The hotel  not support this room quantity']]);
                    }

                }else{
                    return response()->json([
                        'error' => true,
                        'errors' => ['The hotel not exist']]);
                }
            }else{
                return response()->json([
                    'error' => true,
                    'errors' => ['The accommodation/type already exists']]);
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
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }
}
