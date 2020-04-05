@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!


                    {{-- {{ var_dump($friends) }} --}}
{{--
                    @foreach ($friends as $friend)
                        <div style="display:flex;">
                            <p style="margin: 5px">{{ $friend->name }}</p>
                            <p style="margin: 5px">{{ $friend->surname }}</p>
                            @foreach ($friend->getBestFriends as $best_friend)
                                <p style="margin: 5px">{{ $best_friend->friend->name }}</p>
                            @endforeach
                        </div>
                    @endforeach --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
