@extends('layouts.main')

@section('content')
    @include('includes.navigation')

    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                <h4 class="text-center">Post a tweet!</h4>
                <form action="{{ route('save_post') }}" method="post" enctype="multipart/form-data">@csrf
                    <div class="form-group">
                        <textarea class="form-control" name="post" rows="3"></textarea>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Post</button>
                </form>
            </div>
        </div>
        

    </div>

@endsection