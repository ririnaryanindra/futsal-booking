@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Dashboard</h2>

    <div class="row">

        <!-- Total Lapangan -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5>Total Lapangan</h5>
                    <h2>{{ $totalFields }}</h2>
                </div>
            </div>
        </div>

        <!-- Total Booking -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5>Total Booking</h5>
                    <h2>{{ $totalBookings }}</h2>
                </div>
            </div>
        </div>

        <!-- Booking Hari Ini -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5>Booking Hari Ini</h5>
                    <h2>{{ $todayBookings }}</h2>
                </div>
            </div>
        </div>

    </div>
@endsection
