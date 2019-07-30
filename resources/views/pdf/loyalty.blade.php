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
  <strong>Loyalty for month: {{$month}} </strong><br>
	Date: {{$curr_date}}<br>

    <table class="table table-striped" >
	    <tr>
		    <th>Vehicle Reg No</th>
		    <th>Total Volume</th>
	    </tr>

	    @foreach($txns as $txn)
	    <tr>
	    	<td>{{$txn['vehregno']}}</td>
		    <td>{{$txn['total_vol']}}</td>
		</tr>
    @endforeach
	</table>

</body>
</html>
