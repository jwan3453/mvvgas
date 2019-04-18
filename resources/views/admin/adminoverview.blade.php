@extends('template.layout')

@section('content')
	<div class="site-header">
		<div class="site-logo"></div>
		<div class="header-btn">See Closed Issue</div>
		<div class="logout-btn">Logout</div>
	</div>
	
	<div class="map-overview">
		<a href="/adminlocationview/1">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #1</div>
				@include('partial.map1', ['type'=>'small'])
				<div class="issue-count" id="store1IssueCount">1232132</div>
			</div>
		</a>
		
		<a href="/adminlocationview/2">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #2</div>
				@include('partial.map2', ['type'=>'small'])
				<div class="issue-count" id="store2IssueCount"></div>
			</div>
		</a>
		
		<a href="/adminlocationview/3">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #3</div>
				@include('partial.map3', ['type'=>'small'])
				<div class="issue-count" id="store3IssueCount"></div>
			</div>
		</a>
		
		
		<a href="/adminlocationview/4">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #4</div>
				@include('partial.map4', ['type'=>'small'])
				<div class="issue-count" id="store4IssueCount"></div>
			</div>
		</a>
		
		<a href="/adminlocationview/5">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #5</div>
				@include('partial.map5', ['type'=>'small'])
				<div class="issue-count" id="store5IssueCount"></div>
			</div>
		</a>
		
		<a href="/adminlocationview/10">
			<div class="map-grid">
				<div class="store-name">Tacoma Express #10</div>
				@include('partial.map10', ['type'=>'small'])
				<div class="issue-count" id="store10IssueCount"></div>
			</div>
		</a>
	</div>
	
	
	<div class="issue-list-table">
		<div class="issue-list-header">
			<div class="issue-header-text">Open Issues</div>
			<div class="view-issue-btn">See Closed Issue</div>
		</div>
		
		<table id="table">
			<thead>
			<tr>
				<th data-sortable="true" data-field="timestamp">Time Stamp</th>
				<th data-field="status">Status</th>
				<th data-field="diagnosedIssues">Diagnosed Issue(s)</th>
				<th data-field="description">Description</th>
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
									"diagnosedIssues": issue.diagnosed_issue,
									"description":issue.description,
									"feature":issue.feature
									
									
								}
							)
						
							if(issue.feature.indexOf('Pump') !== -1) {
								var pumpNo = /(?!Pump #)\d+/.exec(issue.feature);
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
						});
						
						storeCount.map((countItem) => {
							//console.log('#store' + countItem.location + 'IssueCount');
							$('#store' + countItem.location + 'IssueCount').text("Current issues: "+ countItem.issueCount);
						})
						
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(textStatus)
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