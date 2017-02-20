@if (Session::has('flash_error'))
    <div id="flash_error">{!! Session::get('flash_error') !!}</div>
@endif