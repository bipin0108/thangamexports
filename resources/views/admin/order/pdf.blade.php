<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thangam Exports</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"> 
    <style>
    	.table td, .table th {border-top:0px;padding: 5px;}
    	.header img { float: left; }
		.header h4 { position: relative; top: 15px; left: 10px;}
		.header-right{top: 10px; position: absolute; right: 20px;} 
		.header p{position: relative;left: 10px;font-size: 10px;}
		.pi-item span{position: relative; top: 15px; padding-left: 10px;}
		.col-md-4{width: 33%;float: left;}
		.row{display: inline-block;}
    </style>
  </head>
  <body>
	<div class="container-fluid">
		<div class="header">
		  	<img height="90px;" src="{{ asset('public/image/logo_pdf.png') }}" alt=""> 
		  	<h4>Thangam Exports
			  	<p>
			  		69-A, 2nd Floor, Sir Shanmugam Road (East), <br/> 
			  		R.S. Puram, COIMBTURE - 641002 (TN), INDIA
		 	 	</p>
		   	</h4>
		  
		</div>
		<div class="text-right header-right">
			<p>
				Email: thamgamexports96@gmail.com<br/>
				Mobile: +91 8347779606<br/>
			</p>
		</div>

		<p><br></p>
		 	
		ORDER NO: {{ $order->order_no }} - Total Weight: {{ $order->weight }} - Total Qty: {{ $order->qty }}
		<table class="table" border="0">
			<tr>
				@php($idx=1)
				@foreach($order_items as $item)
					<td class="item"> 
						<table style="border: 1px solid #000;">
							<tr>
								<td style="width: 50%;">
									<span>{{ $item->product_code }}</span><br>
									<img src="https://thangamexports.s3.ap-south-1.amazonaws.com/{{ $item->image }}" alt="" height="100px;" width="100px;">
								</td>
								<td style="border-left:1px solid #000;width: 50%;padding: 0px;margin: 0px;">
									<table style="width: 100%;padding: 0px;margin: 0px;">
										<tr>
											<td style="border-bottom: 1px solid #000;">W:{{ $item->weight }}</td>
										</tr>
										<tr>
											<td style="border-bottom: 1px solid #000;">Q:{{ $item->qty }}</td>
										</tr>
									</table> 
								</td>
							</tr>
							<tr>
								<td colspan="2" style="border-top:1px solid #000;">{{ $item->note }}</td>
							</tr>
						</table>
					</td>
					@if($idx % 3 == 0)
						</tr> 
						<tr>
					@endif
					@php($idx++)
				@endforeach
			</tr>
		</table>

 	</div>
  </body>
</html>