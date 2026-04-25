<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFields = Field::count();
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate('booking_date', today())->count();

        return view('dashboard', compact(
            'totalFields',
            'totalBookings',
            'todayBookings'
        ));
    }
}
