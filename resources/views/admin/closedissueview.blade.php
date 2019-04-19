@extends('template.layout')

@section('resource')
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@stop

@section('content')
	<div class="site-header">
		<div class="site-logo"></div>
		<div class="header-btn">See Closed Issue</div>
		<div class="logout-btn">Logout</div>
	</div>
	
	<div style="padding: 10px 80px">
		<div class="search-bar">
			<div class="date-filter-section">
				<label>Date Range</label>
				<div class="date-field">
					<input id="startDate" type="text" />
					<span> - </span>
					<input id="endDate" type="text" />
				</div>
			</div>
			
			<div class="common-filter-section">
				<label>Reported Issue</label>
				<select id="reportedIssueSelect">
					<option data-id=''>All Reported Issue</option>
				</select>
			</div>
			
			<div class="common-filter-section">
				<label>Diagnosed Issues</label>
				<select id="diagnosedIssueSelect">
					<option data-id=''>All Diagnosed Issue</option>
				</select>
			</div>
			
			<div class="common-filter-section">
				<label>location</label>
				<select id="locationSelect">
					<option data-id=''>All Locations</option>
				</select>
			</div>
			
			<div class="common-filter-section">
				<label>feature</label>
				<select id="featureSelect">
					<option data-id='all'>All Feature</option>
				</select>
			</div>
			
			<div class="search-btn" id="searchBtn">search</div>
			<div class="export-data-btn" id="exportDataBtn">export data</div>
			
		</div>
	</div>
	
	<div class="issue-list-table">

		
		<table
			id="table"
			data-pagination="true"
			data-side-pagination="server"
			data-query-params="filterQueryParams"
			data-ajax-options="ajaxOptions"
			data-url="/api/issues"
		>
			<thead>
			<tr>
				<th data-sortable="true" data-field="closedAt">Close Date</th>
				<th data-field="status">Status</th>
				<th data-field="reportedIssue">Reported Issue</th>
				<th data-field="diagnosedIssues">Diagnosed Issue(s)</th>
				<th data-field="description">Description</th>
				<th data-sortable="true" data-field="feature">Feature</th>
			</tr>
			</thead>
		</table>
	</div>
	
	<div class="page-modal" id="pageModal">
		<div class="export-data-panel">
			<div class="title" >
				Export data
			</div>
			
			<div class="export-filters">
				
				<div class="date-filter-section" style="margin-left:10px;">
					<label>Date Range</label>
					<div class="date-field">
						<input id="startDateExport" type="text" placeholder="start date" />
						<span>  -  </span>
						<input id="endDateExport" type="text" placeholder="end date"/>
					</div>
				</div>
			
				<div class="common-filter-section">
					<label>Reported Issue</label>
					<select id="reportedIssueSelectExport">
						<option data-id=''>All Reported Issue</option>
					</select>
				</div>
			
				<div class="common-filter-section">
					<label>Diagnosed Issues</label>
					<select id="diagnosedIssueSelectExport">
						<option data-id=''>All Diagnosed Issue</option>
					</select>
				</div>
			
				<div class="common-filter-section">
					<label>location</label>
					<select id="locationSelectExport">
						<option data-id=''>All Locations</option>
					</select>
				</div>
			
				<div class="common-filter-section">
					<label>feature</label>
					<select id="featureSelectExport">
						<option data-id='all'>All Feature</option>
					</select>
				</div>
				
			</div>
			
			<div class="export-to-file-btn" id="exportToFileBtn">Export</div>
			
		</div>
	</div>
	
@stop

@section('script')
	<script type="text/javascript">
		
		function filterQueryParams(params) {
			var startDate = $('#startDate').val();
			var endDate = $('#endDate').val();
			var reportedIssue = $('#reportedIssueSelect option:selected').attr('data-id');
			var diagnosedIssue = $('#diagnosedIssueSelect option:selected').attr('data-id');
			var location = $('#locationSelect option:selected').attr('data-id');
			var featureText = $('#featureSelect option:selected').text();
			var featureDataId = $('#featureSelect option:selected').attr('data-id');
			
			params.status = 'closed';
			if( startDate.trim() !== '') {
				params.startDate = startDate;
			}
			if( endDate.trim() !== '') {
				params.endDate = endDate;
			}
			if( reportedIssue.trim() !== '') {
				params.reportedIssue = reportedIssue;
			}
			if( diagnosedIssue.trim() !== '') {
				params.diagnosedIssue = diagnosedIssue;
			}
			if( location.trim() !== '') {
				params.location = location;
			}
			if( featureText.trim() !== '' && featureDataId.trim() !== 'all') {
				params.feature = featureText;
			}

			return params;
		}
		
		$(document).ready(function(){
			
			var tokenObj = localStorage.getItem('token');
			var token = '';
			if(tokenObj) {
				token = JSON.parse(tokenObj).token
			}
			
			window.ajaxOptions = {
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Accept', 'application/json');
					xhr.setRequestHeader('Content-Type', 'application/json');
					xhr.setRequestHeader('Authorization', 'Bearer ' + token);
				}
			}
			
			
			if(!token) {
				window.location.href = "/login";
			}  else {
			
			}
			
			$( "#startDate" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "#endDate" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "#startDateExport" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "#endDateExport" ).datepicker({ dateFormat: 'yy-mm-dd' });
			
			
			var tableData = [];
			var issueItems = [];
			var locations = [];
			var storeFeatures = [];
			
			// get all issue items
			$.ajax({
				type: 'GET',
				async: false,
				url: ' /api/issueitems',
				dataType: 'json',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json',
					'Authorization': 'Bearer ' + token
					
				},
				success: function (data) {
					if(data.status === 'ok') {
						issueItems = data.issueItems;
						for(var i =0; i<issueItems.length; i++) {
							$('#reportedIssueSelect').append(
								'<option data-id='+issueItems[i].id+'>'+issueItems[i].name+'</option>'
							)
							
							$('#diagnosedIssueSelect').append(
								'<option data-id='+issueItems[i].id+'>'+issueItems[i].name+'</option>'
							)
							
							$('#reportedIssueSelectExport').append(
								'<option data-id='+issueItems[i].id+'>'+issueItems[i].name+'</option>'
							)
							
							$('#diagnosedIssueSelectExport').append(
								'<option data-id='+issueItems[i].id+'>'+issueItems[i].name+'</option>'
							)
							
						}
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(textStatus)
				}
			});
			
			// get all locations
			$.ajax({
				type: 'GET',
				async: false,
				url: ' /api/locations',
				dataType: 'json',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json',
					'Authorization': 'Bearer ' + token
					
				},
				success: function (data) {
					if(data.status === 'ok') {
						locations = data.locations;
						for(var i =0; i<locations.length; i++) {
							$('#locationSelect').append(
								'<option data-id='+locations[i].id+'>'+locations[i].name+'</option>'
							)
							
							$('#locationSelectExport').append(
								'<option data-id='+locations[i].id+'>'+locations[i].name+'</option>'
							)
							
						}
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(textStatus)
				}
			});
			
			//get all store feature
			$.ajax({
				type: 'GET',
				async: false,
				url: ' /api/storefeatures',
				dataType: 'json',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json',
					'Authorization': 'Bearer ' + token
					
				},
				success: function (data) {
					if(data.status === 'ok') {
						storeFeatures = data.storeFeatures;
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(textStatus)
				}
			});
			
			$('#locationSelect').change(function(){
				var location = $(this).children('option:selected').attr('data-id');
				$('#featureSelect').empty().append('<option data-id="">All Features</option>');
				$('#featureSelectExport').empty().append('<option data-id="">All Features</option>');
				for(var i=0 ; i<storeFeatures.length; i++) {
					if(parseInt(storeFeatures[i].location) === parseInt(location) ) {
						$('#featureSelect').append(
							'<option data-id='+storeFeatures[i].feature+'>'+storeFeatures[i].feature+'</option>'
						)
					}
				}
				
				for(var i=0 ; i<storeFeatures.length; i++) {
					if(parseInt(storeFeatures[i].location) === parseInt(location) ) {
						$('#featureSelectExport').append(
							'<option data-id='+storeFeatures[i].feature+'>'+storeFeatures[i].feature+'</option>'
						)
					}
				}
				
			})
			
			
			$('#locationSelectExport').change(function(){
				var location = $(this).children('option:selected').attr('data-id');
				$('#featureSelectExport').empty().append('<option data-id="">All Features</option>');
				for(var i=0 ; i<storeFeatures.length; i++) {
					if(parseInt(storeFeatures[i].location) === parseInt(location) ) {
						$('#featureSelectExport').append(
							'<option data-id='+storeFeatures[i].feature+'>'+storeFeatures[i].feature+'</option>'
						)
					}
				}
			})
			
			
			$('#searchBtn').click(function(){
				$('#table').bootstrapTable('refresh');
			})
			
			
			$('#exportDataBtn').click(function(){
				
				$('#startDateExport').val($('#startDate').val());
				$('#endDateExport').val($('#endDate').val());
				
				$('#reportedIssueSelectExport').val($('#reportedIssueSelect').val());
				$('#diagnosedIssueSelectExport').val($('#diagnosedIssueSelect').val());
				
				$('#locationSelectExport').val($('#locationSelect').val());
				$('#featureSelectExport').val($('#featureSelect').val());
				
				$('#pageModal').fadeIn();
			})
			
			$('#exportToFileBtn').click(function() {
				
				var startDate = $('#startDateExport').val();
				var endDate = $('#endDateExport').val();
				var reportedIssue = $('#reportedIssueSelectExport option:selected').attr('data-id');
				var diagnosedIssue = $('#diagnosedIssueSelectExport option:selected').attr('data-id');
				var location = $('#locationSelectExport option:selected').attr('data-id');
				var featureText = $('#featureSelectExport option:selected').text();
				var featureDataId = $('#featureSelectExport option:selected').attr('data-id');
				
				var queryString = '&status=closed';
				if( startDate.trim() !== '') {
					queryString += '&startDate='+startDate;
				}
				if( endDate.trim() !== '') {
					queryString += '&endDate='+endDate;
				}
				if( reportedIssue.trim() !== '') {
					queryString += '&reportedIssue='+reportedIssue;
				}
				if( diagnosedIssue.trim() !== '') {
					queryString += '&diagnosedIssue='+diagnosedIssue;
				}
				if( location.trim() !== '') {
					queryString += '&location='+location;
				}
				if( featureText.trim() !== '' && featureDataId.trim() !== 'all') {
					queryString += '&feature='+featureText;
				}
				
				window.open("api/exporttofile?" + queryString)
			});
			

			
//			function ajaxRequest(params)  {
//				alert('1123123');
//				$.ajax({
//					type: 'GET',
//					async: false,
//					url: ' /api/issues',
//					dataType: 'json',
//					data:{
//						'status':'closed',
//						'reportedIssue':$('#reportedIssueSelect option:selected').attr('data-id'),
//						'diagnosedIssue':$('#diagnosedIssueSelect option:selected').attr('data-id'),
//						'location':$('#locationSelect option:selected').attr('data-id'),
//
//					},
//					headers: {
//						'Accept': 'application/json',
//						'Content-Type': 'application/json',
//						'Authorization': 'Bearer ' + token
//
//					},
//					success: function (data) {
//
//						var dataList = data.issues;
//						tableData = [];
//						for(var i = 0; i< dataList.length ;i++) {
//							tableData.push(
//								{
//									"timestamp":dataList[i].created_at,
//									"status": dataList[i].status,
//									'reportedIssue': dataList[i].reported_issue,
//									"diagnosedIssues": dataList[i].diagnosed_issue,
//									"description":dataList[i].description,
//									"feature":dataList[i].feature
//								});
//						}
//
//						$('#table').bootstrapTable('load', tableData)
//					},
//					error: function (jqXHR, textStatus, errorThrown) {
//						alert(textStatus)
//					}
//				});
//			}
			
			$(function () {
				$('#table').bootstrapTable({
					data: tableData
				});
			});
			
			
		})
		

		
		
		
	</script>
@stop