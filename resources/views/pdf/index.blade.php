<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title> </title>

    </head>
    <body>
        <div class="panel-heading"><h1> Stations </h1> 

    </div>
        @if(count($stations) > 0)
        <?php
            $colcount = count($stations);
            $i = 1;
        ?>
            
            <table class="table table-striped" >
            <tr>
            <th>Id</th>
            <th>Station Name</th>
            </tr>
            @foreach($stations as $station)
            <tr>
            
            <td>{{$station->id}}</td>
            <td>{{$station->station}}</td>
            
            </tr>
                @endforeach
            </table>

        @else
        <p>No stations To Display</p>
        @endif
    </body>
</html>