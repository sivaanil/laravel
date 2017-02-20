@extends('layouts.master')
@section('content')

    <h4>{{$title}}</h4>
    <div id="deviceInfoContainer">


        <table class="table">
            @if (is_array($props) && count($props)>0)
                <thead>
                <tr>
                    <th><?php echo implode('</th><th>', array_keys(current($props))); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($props as $row): array_map('htmlentities', $row); ?>
                <tr>
                    <td><?php echo implode('</td><td>', $row); ?></td>
                    <!-- This can be the get details button
                  <td><button icon="">details</button></td>-->
                </tr>
                <?php endforeach; ?>
                <tbody>
                @else
                    No {{$title}} Available
            @endif
        </table>

    </div>
@stop