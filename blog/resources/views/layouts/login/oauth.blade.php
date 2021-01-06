<div class="card-body">
    <!-- フラッシュメッセージ -->
    @if (session('flash_message'))
        <div class="flash_message">
            {{ session('flash_message') }}
        </div>
    @endif

    <div class="form-group row  offset-md-2">
        <div class="col-md-4 ">
            <a href="{{ 'login/facebook' }}" class="btn btn-block btn-social btn-facebook"  role="button">
                <span class="fa fa-facebook"></span> Facebook  Login
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ 'login/twitter' }}"  class="btn btn-block btn-social btn-twitter" role="button">
                <span class="fa fa-twitter"></span> Twitter Login
            </a>
        </div>
    </div>
</div>