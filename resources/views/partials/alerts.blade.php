@if (session('registered'))
    <div class="alert alert-success">
        @lang('auth.your registration was successful')
    </div>
@endif

@if (session('wrongCredentials'))
    <div class="alert alert-danger">
       @lang('auth.user or pass was wrong')
    </div>
@endif

@if (session('emailHasVerified'))
    <div class="alert alert-success">
       @lang('auth.email has verified') 
    </div>
@endif

@if (session('resetLinkSend'))
    <div class="alert alert-success">
       @lang('auth.reset link send') 
    </div>
@endif

@if (session('resetLinkFailed'))
    <div class="alert alert-danger">
        @lang('auth.reset link failed') 
    </div>
@endif

@if (session('cantChangePassword'))
    <div class="alert alert-danger">
        @lang('auth.cantChangePassword') 
    </div>
@endif

@if (session('passwordChanged'))
    <div class="alert alert-success">
       @lang('auth.passwordChanged') 
    </div>
@endif
