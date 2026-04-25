<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Field;

class BookingController extends Controller
{
    public function index()
    {
        return view('bookings.index', [
            'bookings' => Booking::with('field')->get(),
            'fields' => Field::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'field_id' => 'required',
            'booking_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        // CEK BENTROK
        $exists = Booking::where('field_id', $request->field_id)
            ->where('booking_date', $request->booking_date)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($exists) {
            return response()->json([
                'errors' => ['time' => ['Jadwal bentrok']]
            ], 422);
        }

        $field = Field::find($request->field_id);

        $hours = (strtotime($request->end_time) - strtotime($request->start_time)) / 3600;
        $total = $hours * $field->price_per_hour;

        $booking = Booking::create([
            'customer_name' => $request->customer_name,
            'field_id' => $request->field_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $total
        ]);

        return response()->json($booking->load('field'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'customer_name' => 'required',
            'field_id' => 'required',
            'booking_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        // CEK BENTROK (kecuali dirinya sendiri)
        $exists = Booking::where('field_id', $request->field_id)
            ->where('booking_date', $request->booking_date)
            ->where('id', '!=', $booking->id)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($exists) {
            return response()->json([
                'errors' => ['time' => ['Jadwal bentrok']]
            ], 422);
        }

        $field = Field::find($request->field_id);

        $hours = (strtotime($request->end_time) - strtotime($request->start_time)) / 3600;
        $total = $hours * $field->price_per_hour;

        $booking->update([
            'customer_name' => $request->customer_name,
            'field_id' => $request->field_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $total
        ]);

        return response()->json($booking->load('field'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json(['success' => true]);
    }
}
