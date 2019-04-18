@extends('template.layout')

@section('content')
	
	<div class="site-header">
		<div class="site-logo"></div>
		<div class="header-btn">Main Menu</div>
		<div class="store-text">Tacoma Market #<span id="location">{{$store}}</span></div>
		<div class="logout-btn">Logout</div>
	</div>
	
	@include('partial.map3',['type' => 'regular'])
	
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
	@include('admin.adminPageScript')
@stop