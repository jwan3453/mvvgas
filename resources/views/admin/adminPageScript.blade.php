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
		var dataList = [];
		var issueItems = [];
		
		//fetch open issue list
		fetchOpenIssues();
		function fetchOpenIssues() {
			$.ajax({
				type: 'GET',
				async: false,
				url: '/api/issues/openissues/location/' + $('#location').text() ,
				dataType: 'json',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json',
					'Authorization': 'Bearer ' + token
					
				},
				success: function (data) {
					if(data.status === 'ok') {
						dataList = data.list;
						
						dataList.map((issue) => {
							
							tableData.push(
							{
								"timestamp":issue.created_at,
								"status": issue.status,
								'reportedIssue': issue.reported_issue_text,
								"diagnosedIssues": issue.diagnosed_issue,
								"description":issue.description,
								"feature":issue.feature
								
								
							}
						)
						
						if(issue.feature.toLowerCase().indexOf('pump') !== -1) {
							var pumpNo = issue.feature.match(/Pump #(.*)/)[1]
							$('td').each(function(){
								var obj = $(this);
								if(obj.attr('data-id') == ('{{$store.'-'}}'+pumpNo)) {
									obj.css({
										color:'white',
										background:'red',
									}).attr('has-issue','yes');
								}
							})
						} else if(issue.feature.toLowerCase().indexOf('car wash') !== -1) {
							$('#' + '{{$store.'-carWash'}}').css({
								color:'white',
								background:'red',
							}).attr('has-issue','yes');
						} else if(issue.feature.toLowerCase().indexOf('store') !== -1) {
							$('#' + '{{$store.'-store'}}').css({
								color:'white',
								background:'red',
							}).attr('has-issue','yes');
						}
					});
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(textStatus)
				}
			});
		}
		
		
		// fetch issue item list
		$.ajax({
			type: 'GET',
			async: false,
			url: '/api/issueitems',
			dataType: 'json',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
				'Authorization': 'Bearer ' + token
				
			},
			success: function (data) {
				if(data.status === 'ok') {
					 issueItems = data.issueItems;
					
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
		
		//click the issued facility, will pop up the issue item list
		var currentIssueData = null;
		$('[has-issue="yes"]').click(function(){
			var obj = $(this);
			var dataId = obj.attr('data-id');
			
			$('#issueItemList').empty();
			for(var i = 0; i < issueItems.length; i++) {

				if(dataId.toLowerCase().includes('carwash') && issueItems[i].type.toLowerCase() === 'car wash') {
					// render issue list for carwash
					$('#issueItemList').append(
						'<div class="issue-item" data-id="'+issueItems[i].id+'">'+issueItems[i].name+'</div>'
					);
				}
				
				else if(dataId.toLowerCase().includes('store') && issueItems[i].type.toLowerCase() === 'store'){
					// render issue list for store
					$('#issueItemList').append(
						'<div class="issue-item" data-id="'+issueItems[i].id+'">'+issueItems[i].name+'</div>'
					);
					
				} else if ( parseInt(dataId.split('-')[1]) &&  issueItems[i].type.toLowerCase() === 'pump'){
					// render issue list for pump
					$('#issueItemList').append(
						'<div class="issue-item" data-id="'+issueItems[i].id+'">'+issueItems[i].name+'</div>'
					);
				}
				
			}
			
			
			var issueItemsString = ''
		//	console.log(dataList);
			for(var i=0; i<dataList.length; i++) {
				if(dataId.toLowerCase().includes('carwash') && dataList[i].feature.toLowerCase().includes('car wash')) {
					// highlight  reported issue for store
					//todo
					
					currentIssueData =  dataList[i];
					issueItemsString = dataList[i].reported_issue;
					console.log();
					
				} else if(dataId.toLowerCase().includes('store') && dataList[i].feature.toLowerCase().includes('store')) {
					// highlight  reported issue for store
					//todo
					currentIssueData =  dataList[i];
					issueItemsString = dataList[i].reported_issue;
					
				} else {
					var pumpId = dataId.split('-')[1];
					if(dataList[i].feature.includes('#' + pumpId)) {
						currentIssueData =  dataList[i];
						issueItemsString = dataList[i].reported_issue;
					}
				}
			}
			
			
			if(issueItemsString) {
				var splitedIssueItem = issueItemsString.split(',');
				var reportedIssueString = '';
				for(var j = 0; j<splitedIssueItem.length; j++ ) {
					for(var k=0; k<issueItems.length; k++) {
						if(issueItems[k].id == splitedIssueItem[j]) {
							reportedIssueString += issueItems[k].name + ',  ';
							$('.issue-item[data-id="'+splitedIssueItem[j]+'"]').addClass('item-selected');
						}
					}
				}
				$('#reportedIssueText').text(reportedIssueString);
				$('#reportedIssueItem').val(issueItemsString);
			}
			
			$('#pageModal').fadeIn();
			$('#issueFeatures').text()
			
		})
		
		
		$('.issue-item-list').on('click', 'div', function(){
			if($(this).hasClass('item-selected')) {
				$(this).removeClass('item-selected')
			} else {
				$(this).addClass('item-selected')
			}
		})
		
		$('#issueItemTitle').click(function(){
			closeIssueItemList();
		})
		
		
		function closeIssueItemList (){
			$('#pageModal').fadeOut();
			$('#diagnosedIssuePanel').hide();
			$('#submitIssueBtns').hide();
			$('#issueItemList').show();
			$('#continueBtn').show();
		}
		
		//click continue button and show the diagnosed pannel with description input area
		$('#continueBtn').click(function(){
			var issueItemString = '';
			var diagnosedIssueString = '';
			$('.issue-item-list div').each(function(){
				if($(this).hasClass('item-selected')) {
					for(var i=0; i<issueItems.length; i++) {
						if(issueItems[i].id == $(this).attr('data-id')) {
							diagnosedIssueString += issueItems[i].name + ',  ';
						}
					}
					issueItemString += $(this).attr('data-id') + ','
				}
			})
			
			var reg=/,$/gi;
			diagnosedIssueString = diagnosedIssueString.replace(reg,"");
			issueItemString = issueItemString.replace(reg,"");
			
			$('#diagnosedIssueText').text(diagnosedIssueString);
			$('#diagnosedIssueItem').val(issueItemString);
			
			$('#diagnosedIssuePanel').show();
			$('#submitIssueBtns').show();
			$('#issueItemList').hide();
			$('#continueBtn').hide();
			
		})
		
		
		function submitIssue(issueData){
			$.ajax({
				type: 'PUT',
				async: false,
				url: '/api/issues/' + issueData.id,
				dataType: 'json',
				data:issueData,
				headers: {
					'Accept': 'application/json',
					'Authorization': 'Bearer ' + token
					
				},
				success: function (data) {
					if(data.status === 'ok') {
						toastAlert('operation success',1);
						window.location.reload()
//						closeIssueItemList();
//						fetchOpenIssues();
						
					} else {
						toastAlert('something went wrong, please try again',1);
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(textStatus)
				}
			});
		}
		
		$('#onHoldBtn').click(function(){
			console.log(currentIssueData.description);
			if($('#issueDescription').val() === '') {
				toastAlert('please enter followup on issue',2);
				return;
			}
			if(currentIssueData) {
				currentIssueData.diagnosedIssue = $('#diagnosedIssueItem').val();
				currentIssueData.description = $('#issueDescription').val();
				currentIssueData.status = 'on hold';
				submitIssue(currentIssueData);
			}
			
		})
		
		$('#closeIssueBtn').click(function(){
			if($('#issueDescription').val() === '') {
				toastAlert('please enter followup on issue',2);
				return;
			}
			if(currentIssueData) {
				currentIssueData.diagnosedIssue = $('#diagnosedIssueItem').val();
				currentIssueData.description = $('#issueDescription').val();
				currentIssueData.status = 'closed';
				submitIssue(currentIssueData);
			}
		})
		

	})
</script>