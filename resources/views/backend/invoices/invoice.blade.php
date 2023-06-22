<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
	<style media="all">
        @page {
			margin: 0;
			padding:0;
		}
		body{
			font-size: 0.875rem;
            font-family: '<?php echo  $font_family ?>';
            font-weight: normal;
            direction: <?php echo  $direction ?>;
            text-align: <?php echo  $text_align ?>;
			padding:0;
			margin:0;
		}
		.gry-color *,
		.gry-color{
			color:#878f9c;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .25rem .7rem;
		}
		table.padding td{
			padding: .25rem .7rem;
		}
		table.sm-padding td{
			padding: .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:<?php echo  $text_align ?>;
		}
		.text-right{
			text-align:<?php echo  $not_text_align ?>;
		}
	</style>
</head>
<body>
	<div>

		@php
			$logo = get_setting('system_logo_white');
		@endphp

		<div style="background: #eceff4;padding: 1rem;">
			<table>
				<tr>
					<td>
						@if($logo != null)
							<img src="{{ uploaded_asset($logo) }}" height="30" style="display:inline-block;">
						@else
							<img src="{{ static_asset('assets/img/logo.png') }}" height="30" style="display:inline-block;">
						@endif
					</td>
					<td style="font-size: 1.5rem;" class="text-right strong">{{  translate('INVOICE') }}</td>
				</tr>
			</table>
			<table>
				<tr>
					<td style="font-size: 1rem;" class="strong">{{ get_setting('site_name') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ get_setting('contact_address') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{  translate('Email') }}: {{ get_setting('contact_email') }}</td>
					<td class="text-right small"><span class="gry-color small">{{  translate('Order ID') }}:</span> <span class="strong">{{ $order->code }}</span></td>
				</tr>
				<tr>
					<td class="gry-color small">{{  translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
					<td class="text-right small"><span class="gry-color small">{{  translate('Order Date') }}:</span> <span class=" strong">{{ date('d-m-Y', $order->date) }}</span>
                        <br>
                        @if($order->orderDetails[0]->shipping_type!='home_delivery')
                            <span class="gry-color small">{{  translate('Expected PickUp') }}:</span> <span class=" strong">{{ date('d-m-Y H:i A', $order->delivery_date) }}</span></td>
                        @endif

				</tr>
				<tr>
					<td class="gry-color small"></td>
					<td class="text-right small"><span class="gry-color small">{{  translate('Payment Typs') }}:</span> <span class=" strong">{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</span></td>
				</tr>
			</table>

		</div>

		<div style="padding: 1rem;padding-bottom: 0">
            <table>
            	<thead>
	        		<tr>
	        			<th width="60%"></th>
	        			<th width="40%"></th>
	        		</tr>
        		</thead>
            	<tbody>
            		<tr>
            			<td>
            				<table>
            					<tbody>
									@php
										$shipping_address = json_decode($order->shipping_address);
									@endphp
									<tr><td class="strong small gry-color">{{ translate('Shipping Address') }}:</td></tr>
									<tr><td class="strong">Name: {{ optional($shipping_address)->name }}</td></tr>
									<tr><td class="gry-color small">{{ optional($shipping_address)->address }}, {{ optional($shipping_address)->area }}, {{ optional($shipping_address)->city }}, {{ optional($shipping_address)->country }}</td></tr>
									<tr><td class="gry-color small">{{ translate('Phone') }}: {{ optional($shipping_address)->phone }}</td></tr>
								</tbody>
							</table>
						</td>
            			<td>
            				<table >
            					<tbody>
									@php
										$billing_address = json_decode($order->billing_address);
									@endphp
									<tr><td class="strong small gry-color">{{ translate('Billing Address') }}:</td></tr>
									<tr><td class="strong">Name: {{ optional($billing_address)->name }}</td></tr>
									<tr><td class="gry-color small">{{ optional($billing_address)->address }}, {{ optional($billing_address)->area }}, {{ optional($billing_address)->city }}, {{ optional($billing_address)->country }}</td></tr>
									<tr><td class="gry-color small">{{ translate('Phone') }}: {{ optional($billing_address)->phone }}</td></tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

	    <div style="padding: 1rem;">
			<table class="padding text-left small border-bottom">
				<thead>
	                <tr class="gry-color" style="background: #eceff4;">
	                    <th width="30%" class="text-left">{{ translate('Product Name') }}</th>
						<th width="20%" class="text-left">{{ translate('Delivery Type') }}</th>
	                    <th width="10%" class="text-left">{{ translate('Qty') }}</th>
	                    <th width="15%" class="text-left">{{ translate('Unit Price') }}</th>
	                    <th width="10%" class="text-left">{{ translate('Tax') }}</th>
	                    <th width="15%" class="text-right">{{ translate('Total') }}</th>
	                </tr>
				</thead>
				<tbody class="strong">
	                @foreach ($order->orderDetails as $key => $orderDetail)
		                @if ($orderDetail->product != null)
							<tr class="">
								<td>{{ $orderDetail->product->name }} @if($orderDetail->variation != null) ({{ $orderDetail->variation }}) @endif</td>
								<td>
									@if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
										{{ translate('Home Delivery') }}
									@elseif ($orderDetail->shipping_type == 'pickup_point')
										@if ($orderDetail->pickup_point != null)
											{{ $orderDetail->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
										@endif
                                        @if ($orderDetail->time_slot != null)
                                        {{-- @php
                                            $time=\App\PickupTime::findOrFail($orderDetail->time_slot);

                                        @endphp
                                        <br> Days: {{ implode(",",json_decode($time->days)) }} <br> From : {{ $time->start_time }} to {{ $time->end_time }} --}}

                                    @endif
									@endif
								</td>
								<td class="">{{ $orderDetail->quantity }}</td>
								<td class="currency">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
								<td class="currency">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td>
			                    <td class="text-right currency">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
							</tr>
		                @endif
					@endforeach
	            </tbody>
			</table>
		</div>

	    <div style="padding:0 1.5rem;">
	        <table class="text-right sm-padding small strong">
	        	<thead>
	        		<tr>
	        			<th width="60%"></th>
	        			<th width="40%"></th>
	        		</tr>
	        	</thead>
		        <tbody>
			        <tr>
			            <td>
			            </td>
			            <td>
					        <table class="text-right sm-padding small strong">
						        <tbody>
							        <tr>
							            <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
							            <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}</td>
							        </tr>
							        <tr>
							            <th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
							            <td class="currency">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
							        </tr>
							        <tr class="border-bottom">
							            <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
							            <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
							        </tr>
				                    <tr class="border-bottom">
							            <th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
							            <td class="currency">{{ single_price($order->coupon_discount) }}</td>
							        </tr>
							        <tr>
							            <th class="text-left strong">{{ translate('Grand Total') }}</th>
							            <td class="currency">{{ single_price($order->grand_total) }}</td>
							        </tr>
						        </tbody>
						    </table>
			            </td>
			        </tr>
		        </tbody>
		    </table>
            <div class="row">
                <h5><b class="mx-3">Note ::</b>
                {{ $order->note }} </h5>
            </div>
	    </div>

	</div>
</body>
</html>
