@extends('layouts.app')

@section('content')
    <form route="/user/avatar" method="POST" enctype='multipart/form-data'>
        @method('PATCH')
        @csrf

        <div>
            <input type="file" name="image">
        </div>
        <input type="submit" value="更新する">
    </form>
@endsection