<div class="map-container-wrapper" >
	<div class={{ $type == 'small'?"map-container-small":"map-container"  }}>
		
		<div data-id="5-store" class={{ $type == 'small'?"express5-store-small":"express5-store-regular"  }}>
			Store
		</div>
		
		<table class={{ $type == 'small'?"express5-bottom-small":"express5-bottom-regular" }}  cellpadding="0" cellspacing="0">
			
			{{--<tr>--}}
				{{--<td rowspan="2">10</td>--}}
				{{--<td rowspan="2">9</td>--}}
				{{--<td rowspan="2"></td>--}}
				{{--<td rowspan="2"></td>--}}
				{{--<td rowspan="2">2</td>--}}
				{{--<td rowspan="2">1</td>--}}
			{{--</tr>--}}
			
			
			<tr>
				<td data-id="5-10" style= {{ $type == 'small'?"padding:8px 4px;":"padding:40px 20px;" }} >10</td>
				<td data-id="5-9" style={{ $type == 'small'?"padding:8px 4px;":"padding:40px 20px;" }}>9</td>
				<td data-id="5-2" style="visibility: hidden">2</td>
				<td data-id="5-1" style="visibility: hidden">1</td>
				<td data-id="5-2" style={{ $type == 'small'?"padding:8px 4px;":"padding:40px 20px;" }}>2</td>
				<td data-id="5-1" style={{ $type == 'small'?"padding:8px 4px;":"padding:40px 20px;" }}>1</td>
				
			</tr>
			<tr>

			</tr>

			<tr>
				<td data-id="5-8">8</td>
				<td data-id="5-7">7</td>
				<td data-id="5-6" rowspan="2">6</td>
				<td data-id="5-5" rowspan="2">5</td>
				<td data-id="5-4">4</td>
				<td data-id="5-3">3</td>
			</tr>
			
			<tr>
				<td data-id="5-8D">8D</td>
				<td data-id="5-7D">7D</td>
				<td data-id="5-4D">4D</td>
				<td data-id="5-3D">3D</td>
			
			</tr>
		
		
		</table>
	
	</div>
</div>