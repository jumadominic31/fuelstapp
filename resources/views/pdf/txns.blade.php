<!DOCTYPE html>
<html>
<head>

	<title>Transactions</title>
	<style>
	    @page { margin: 100px 25px; font-family:  Helvetica, Arial, sans-serif;}
	    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 60px; }
	    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
	    p { page-break-after: always; }
	    p:last-child { page-break-after: never; }
      table { border-collapse: collapse;  width: 100%; font-size: 12px;}
      table, th, td { border: 1px solid black; }
	  </style>
</head>
<body>
	<header style="text-align: center">{{$company_details[0]['name']}} <br> {{$company_details[0]['address']}}, {{$company_details[0]['city']}} <br> Phone: {{$company_details[0]['phone']}}</header>
	<footer style="text-align: right">Powered by QBS - info@quadcorn.co.ke</footer>
  <strong>Transactions data </strong><br>
	Date: {{$curr_date}}<br>

	Total Sales : <strong> {{number_format($tot_coll, 2)}} </strong><br>

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
        @foreach($txns as $txn)
        <tr>
	        <td>{{$txn['receiptno']}}</td>
	        <td>{{$txn['vehregno']}}</td>
	        <td style="text-align: right;">{{number_format($txn['amount'], 2)}}</td>
	        <td style="text-align: right;">{{number_format($txn['volume'], 2)}}</td>
	        <td style="text-align: right;">{{number_format($txn['sellprice'], 2)}}</td>
	        <td>{{$txn['fueltype']}}</td>
	        <td>{{$txn['paymethod']}}</td>
	        <td>{{$txn['created_at']}}</td>
	        <td>{{$txn['user']['fullname']}}</td>
	        <td>{{$txn['station']['station']}}</td>
        </tr>
        @endforeach
        </table>

</body>
</html>
