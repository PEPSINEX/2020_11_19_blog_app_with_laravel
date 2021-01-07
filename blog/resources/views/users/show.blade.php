@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <b>プロフィール設定</b>
                </div>

                <div class="card-body">
                    <h5 class="card-title">
                        <b>アイコン</b>
                    </h5>

                    <div class="row no-gutters">
                        <div class="col-md-1">
                            <img src="{{ $image_path }}" width="100%" height="100%">
                        </div>
                        <a href="#" class="card-link ml-3">変更</a>
                    </div>

                    <form action="/avatar" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="image">
                        <input type="submit" value="アイコンアップロード">
                    </form>

                    <form action="avatar_delete" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="画像削除">
                    </form>
                </div>

                <div class="card-body">
                    <h5 class="card-title">
                        <b>ユーザー名</b>
                    </h5>
                    <p class="card-text">
                        {{ $user->name }}
                        <a href="#" class="card-link ml-3">変更</a>
                    </p>
                </div>

                <div class="card-body">
                    <h5 class="card-title">
                        <b>メールアドレス</b>
                    </h5>
                    <p class="card-text">
                        {{ $user->email ?: '未設定' }}
                        <a href="#" class="card-link ml-3">変更</a>
                    </p>
                </div>

                <div class="card-body">
                    <h5 class="card-title">
                        <b>生年月日</b>
                    </h5>
                    <p class="card-text">
                        {{ $user->birth_date ?: '未設定' }}
                        <a href="#" class="card-link ml-3">変更</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <form route="/users/{$user->id}" method="POST" enctype='multipart/form-data'>
    @method('PATCH')
    @csrf

    @isset ($filename)
        <div>
            <img src="{{ asset('storage/avatar/' . $user->image_path) }}">
        </div>
    @endisset

    <div>
        <input type="file" name="image">
    </div>
    <input type="submit" value="更新する">
</form> -->
@endsection