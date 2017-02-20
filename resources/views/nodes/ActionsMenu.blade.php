@extends("layouts.master")
@section("content")
    {{ HTML::style( asset('css/actionsmenu.css') ) }}
    <div id="mainActionContainer">
        <span class="largeText">Actions</span>

        <div id="actionButtonContainer">
            @foreach($buttonList as $button)
                <a class="button radius" {{$button['command']}}>{{$button['dispText']}}</a>
                @if(isset($button['description']) && strlen($button['description'])>0)
                    @if(isset($button['name']) && strlen($button['name'])>0)
                        <label id="{{$button['name']}}">{{$button['description']}}</label>
                    @else
                        <label>{{$button['description']}}</label>
                    @endif
                @endif
                <br>
            @endforeach
        </div>
    </div>
    <script>
        /*function init()
         {*/
        setInterval(function () {
            $.ajax({
                type: 'GET',
                url: '/updateScanInfo',
                data: {nodeId: {{$nodeId}}},

                success: function (result) {
                    var scanInfo = JSON.parse(result)
                    //console.log(scanInfo);
                    if (typeof scanInfo.scan != 'undefined') {
                        $('#scan').html(scanInfo.scan)
                        //$('#sampleLable').text(scanInfo[scan])
                    }
                    if (typeof scanInfo.alarm != 'undefined') {
                        $('#alarm').html(scanInfo.alarm)
                    }
                    if (typeof scanInfo.prop != 'undefined') {
                        $('#prop').html(scanInfo.prop)
                    }

                }
            });
        }, 5000); //5 seconds
        //}

    </script>
@stop