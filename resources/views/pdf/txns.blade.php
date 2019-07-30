<!DOCTYPE html>
<html>
<head>

	<title>Transactions</title>
	<style>
	    @page { margin: 100px 25px; }
	    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 60px; }
	    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
	    p { page-break-after: always; }
	    p:last-child { page-break-after: never; }
      table { border-collapse: collapse; }
      table, th, td { border: 1px solid black; }
	  </style>
</head>
<body>
	<header>{{$company_details[0]['name']}} <br> {{$company_details[0]['address']}}, {{$company_details[0]['city']}} <br> Phone: {{$company_details[0]['phone']}}</header>
  <footer>Powered by Avanet Technologies</footer>
  <strong>Transactions data </strong><br>
	Date: {{$curr_date}}<br>

	Total Sales : <strong> {{$tot_coll}} </strong><br>

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
	        <td>{{$txn['amount']}}</td>
	        <td>{{$txn['volume']}}</td>
	        <td>{{$txn['sellprice']}}</td>
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
