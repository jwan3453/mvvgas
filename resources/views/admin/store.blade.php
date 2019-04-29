@extends('template.layout')

@section('resource')
@stop

@section('content')
	
	<div class="site-header">
		<div class="site-logo"></div>
		<a href="/adminoverview"><div class="header-btn">Main Menu</div></a>
		<div class="store-text">Tacoma Market #<span id="location">{{$store}}</span></div>
		<a href="/logout"><div class="logout-btn">Logout</div></a>
	</div>
	
	
	@include('partial.map'.$store,['type' => 'regular'])
	
	
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
				<th data-field="feature">Feature</th>
			</tr>
			</thead>
		</table>
	</div>
	
	<div class="page-modal" id="pageModal">
	
		<div class="issue-panel">
			<div class="title" id="issueItemTitle">
				Issue on <span id="issueFeatures"></span>
				<div class="close-panel-icon"></div>
			</div>
			
			<div style="padding:30px;">
				<div class="issue-reported">
					<label>Issue Reported:</label>
					<div id="reportedIssueText"></div>
				</div>
				
				
				<div class="diagnosed-issue-panel" style="display: none" id="diagnosedIssuePanel">
					<div class="issue-diagnosed">
						<label>Diagnosed issue(s):</label>
						<div id="diagnosedIssueText"></div>
					</div>
					
					<div class="issue-description">
						<label>Enter followup on issue</label>
						<textarea id="issueDescription"></textarea>
					</div>
					
				</div>
				
				
				<div class="issue-item-list" id="issueItemList">
				
				</div>
				
				
				<div class="option-btns">
					
					<div class="submit-issue-btns" id="submitIssueBtns" >
						<div id="onHoldBtn" class="option-btn on-hold-btn">
							On Hold
						</div>
						<div id="closeIssueBtn" class="option-btn close-issue-btn">
							Close
						</div>
					
					</div>
					
					<div id="continueBtn" class="option-btn continue-btn">
						Continue
					</div>
				</div>
				
			</div>
		</div>
		
		
	</div>
	
	<input type="hidden" id="reportedIssueItem" />
	<input type="hidden" id="diagnosedIssueItem" />
	

	

@stop

@section('script')
	
	@include('admin.adminPageScript')

@stop