@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="body">
            <form id="sign_in" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="msg">Sign in to start your session</div>
                <div class="input-group{{ $errors->has('usernameOrEmail') ? ' has-error' : '' }}">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                    <div class="form-line">
                        <input type="text" class="form-control" name="usernameOrEmail" placeholder="Username" value="{{ old('username') }}" required autofocus>
                    </div>
                    @if ($errors->has('usernameOrEmail'))
                        <span class="help-block">
                            <strong>{{ $errors->first('usernameOrEmail') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8 p-t-5">
                        <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-green">
                        <label for="rememberme">Remember Me</label>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn bg-green waves-effect" type="submit">SIGN IN</button>
                    </div>
                </div>
                {{--<div class="row m-t-15 m-b--20">--}}
                {{--<div class="col-xs-6">--}}
                {{--<a href="sign-up.html">Register Now!</a>--}}
                {{--</div>--}}
                {{--<div class="col-xs-6 align-right">--}}
                {{--<a href="forgot-password.html">Forgot Password?</a>--}}
                {{--</div>--}}
                {{--</div>--}}
            </form>
        </div>
    </div>
@endsection
