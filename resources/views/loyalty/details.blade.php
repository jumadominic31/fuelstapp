<div class="row">

        
    @if(count($txns) > 0)
        <?php
            $colcount = count($txns);
            $i = 1;
        ?>
         
        <table class="table table-striped" >
        <tr>
        <th>Receipt No</th>
        <th>Vehicle Reg No</th>
        <th>Amount (KShs)</th>
        <th>Volume (l)</th>
        <th>Rate</th>
        <th>Fuel Type</th>
        <th>Payment Method</th>
        <th>Txn Date/Time</th>
        <th>User ID</th>
        <th>Station ID</th>
        </tr>
        <?php 
            $sum_tot_vol = 0;
        ?>
        @foreach($txns as $txn)
        <tr>
        
        <td>{{$txn['receiptno']}}</td>
        <td>{{$txn['vehregno']}}</td>
        <td>{{$txn['amount']}}</td>
        <td>{{$txn['volume']}}</td>
        <td>{{$txn['sellprice']}}</td>
        <td>{{$txn['fueltype']}}</td>
        <td>{{$txn['paymethod']}}</td>
        <td>{{$txn['created_at']}}</td>
        <td>{{$txn['user']['fullname']}}</td>
        <td>{{$txn['station']['station']}}</td>

        <?php
                $sum_tot_vol += $txn['volume'];
        ?>
        </tr>
        @endforeach
        <tr>
        <th></th>
        <th></th>
        <th></th>
        <th>{{$sum_tot_vol}}</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        </tr>
        </table>
        {{$txns->links()}}
        
    @else
      <p>No txns To Display</p>
    @endif
</div>