@extends('layouts.app')

@section('content')
<div class="container">
    @include('inc.messages')
    <div class="row">
    <h3>Getting Started Steps</h3>
    Please complete the following steps before you start posting transaction <br>
    1. Create stations <br>
     - Go to Administration menu, then choose stations <br>
     - Click add station <br>
     - Enter station name, diesel opening stock and petrol open stock <br>
    2. Add selling price rates for each station and each fuel type<br>
     - Go to Administration menu, then choose rates <br>
     - Click add rate <br>
     - Select station, start date, end date, fuel type, ERC selling price and buying price <br>   
    3. Add station administrators and attendants for each station<br>
     - Go to Administration menu, then choose users <br>
     - Click add user <br>
     - Enter username, fullname, phone number, password, choose station and set status to active <br> 
    Finally, <br>
    4. Add pumps each station<br>
     - Go to Administration menu, then choose pumps <br>
     - Click add pump <br>
     - Enter pumpname e.g. 'StationName_Diesel_A', choose fueltype, enter pump reading, select station and attendant assigned to the pump <br>  
    <hr>
    <h4> Start using Fuelstapp by Avanet Technologies </h4>
    </div>
</div>
@endsection