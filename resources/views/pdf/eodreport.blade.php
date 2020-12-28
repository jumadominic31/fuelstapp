<!DOCTYPE html>
<html>
<head>

	<title>End of Shift Report</title>
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
	<header style="text-align: center">{{$company_details['name']}} <br> {{$company_details['address']}}, {{$company_details['city']}} <br> Phone: {{$company_details['phone']}}</header>
    <footer style="text-align: right">Powered by QBS - info@quadcorn.co.ke</footer>

    <div style="text-align: center"><h2>End of Shift Report</h2></div>
	Print Date:  {{$curr_date}}<br><br>

	@include('eodays.daily.shiftdetails')

</body>
</html>