@if (Session::has('flash_message'))
    <div id='flash_message' class="alert alert-warning alert-dismissible" role="alert">
        {!! Session::get('flash_message') !!}
    </div>
@endif