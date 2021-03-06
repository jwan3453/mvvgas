@extends('template.layout')

@section('content')
	<div class="site-header">
		<div class="site-logo"></div>
		<a href="/closedissue"><div class="header-btn">See Closed Issue</div></a>
		<a href="/logout"><div class="logout-btn">Logout</div></a>
	</div>
	
	<div class="map-overview">
		<a href="/adminlocationview/1">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #1</div>
				@include('partial.map1', ['type'=>'small'])
				<div class="issue-count" >Current Issues: <span id="store1IssueCount">0</span></div>
			</div>
		</a>
		
		<a href="/adminlocationview/2">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #2</div>
				@include('partial.map2', ['type'=>'small'])
				<div class="issue-count" >Current Issues: <span id="store2IssueCount">0</span></div>
			</div>
		</a>
		
		<a href="/adminlocationview/3">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #3</div>
				@include('partial.map3', ['type'=>'small'])
				<div class="issue-count" >Current Issues: <span id="store3IssueCount">0</span></div>
			</div>
		</a>
		
		
		<a href="/adminlocationview/4">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #4</div>
				@include('partial.map4', ['type'=>'small'])
				<div class="issue-count" >Current Issues: <span id="store4IssueCount">0</span></div>
			</div>
		</a>
		
		<a href="/adminlocationview/5">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #5</div>
				@include('partial.map5', ['type'=>'small'])
				<div class="issue-count" >Current Issues: <span id="store5IssueCount">0</span></div>
			</div>
		</a>
		
		<a href="/adminlocationview/10">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #10</div>
				@include('partial.map10', ['type'=>'small'])
				<div class="issue-count" >Current Issues: <span id="store10IssueCount">0</span></div>
			</div>
		</a>
	</div>
	
	
	<div class="issue-list-table">
		<div class="issue-list-header">
			<div class="issue-header-text">Open Issues</div>
			<a href="/closedissue"><div class="view-issue-btn">See Closed Issue</div></a>
		</div>
		
		<table id="table">
			<thead>
			<tr>
				<th data-sortable="true" data-field="timestamp">Time Stamp</th>
				<th data-field="status">Status</th>
				<th data-field="reportedIssue">Reported Issue</th>
				<th data-field="diagnosedIssues">Diagnosed Issue(s)</th>
				<th data-field="description">Description</th>
				<th data-field="location">Location</th>
				<th data-sortable="true" data-field="feature">Feature</th>
			</tr>
			</thead>
		</table>
	</div>
	
@stop




@section('script')
	<script type="text/javascript">
		$(document).ready(function() {
			
			var tokenObj = localStorage.getItem('token');
			var token = '';
			if(tokenObj) {
				token = JSON.parse(tokenObj).token
			}
			
			if(!token) {
				window.location.href = "/login";
			}  else {
			
			}
			
			var tableData = [];
			
			$.ajax({
				type: 'GET',
				async: false,
				url: '/api/issues/openissues',
				dataType: 'json',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json',
					'Authorization': 'Bearer ' + token
					
				},
				success: function (data) {
					if(data.status === 'ok') {
						var list = data.list;
						var storeCount = data.count;
						
						list.map((issue) => {
							
							tableData.push(
								{
									"timestamp":issue.created_at,
									"status": issue.status,
									'reportedIssue':issue.reported_issue_text,
									"diagnosedIssues": issue.diagnosed_issue,
									"description":issue.description,
									"location": issue.location_text,
									"feature":issue.feature
									
									
									
								}
							)
						$('#table').bootstrapTable('refresh');
						
							if(issue.feature.indexOf('Pump') !== -1) {
								var pumpNo = issue.feature.match(/Pump #(.*)/)[1];
								$('td').each(function(){
									var obj = $(this);
									if(obj.attr('data-id') == (issue.location+'-'+pumpNo)) {
										obj.css({
											color:'white',
											background:'red',
										})
									}
								})
							} else if(issue.feature.indexOf('Car Wash') !== -1) {
								$('#' +issue.location+'-carWash').css({
									color:'white',
									background:'red',
								})
							}
							else if(issue.feature.indexOf('Store') !== -1) {
								$('#' +issue.location+'-store').css({
									color:'white',
									background:'red',
								})
							}
						});
						
						storeCount.map((countItem) => {
							//console.log('#store' + countItem.location + 'IssueCount');
							$('#store' + countItem.location + 'IssueCount').text(countItem.issueCount);
						});
						
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					if(errorThrown === 'Unauthorized') {
						localStorage.removeItem('token');
						window.location.href = "/login";
					}
				}
			});
			
			$(function () {
				$('#table').bootstrapTable({
					data: tableData
				});
			});
			
		})
	</script>
@stop