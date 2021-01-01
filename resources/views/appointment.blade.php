@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center">Doctor's Profile</h4>
                    <img src="{{ asset('images') }}/{{ $doctor->image }}" width="100px" alt="" style="border-radius: 50%;">
                    <br>
                    <p class="lead">Name: {{ ucfirst($doctor->name) }}</p>
                    <p class="lead">Education: {{ $doctor->education }}</p>
                    <p class="lead">Expertise: {{ $doctor->department }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <form action="{{ route('home') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header lead">{{ $date }}</div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($times as $time)
                                <div class="col-md-3">
                                    <label class="btn btn-outline-primary">
                                        <input type="radio" name="status" value="1">
                                        <span>{{ $time->time }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success" style="width: 100%">Book Appointment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
