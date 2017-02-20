@if($breadcrumb !='')
    <div id="breadcrumbContainer">
        <div id="breadcrumbButtonLeftDiv">
            <button class="breadcrumbButton" onclick="shiftLeft()">&lt;</button>
        </div>
        <div id="makeMeScrollable">
            @for($i=0; $i<count($breadcrumb); $i++)
                <a id=@if($i==count($breadcrumb)-1)'lastCrumb'@else'crumb{{$i}}' @endif class="parallelogram" href="{{url()}}/home#/nodeChange/{{$breadcrumb[$i]['hyperlink']}}">
                <span class="crumbText{{{ (strlen($breadcrumb[$i]['name']) > 31) ? ' longCrumb' : '' }}}{{{ (strlen($breadcrumb[$i]['name']) > 40) ? ' extraLongCrumb' : '' }}}">{{{$breadcrumb[$i]['name']}}}</span>
                </a>
            @endfor

        </div>
        <div id="breadcrumbButtonRightDiv">
            <button class="breadcrumbButton" onclick="shiftRight()">&gt;</button>
        </div>
    </div>
    <div style="clear:both;"></div>
@endif