<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Issue;
use App\IssueItem;
use App\StoreLocation;
use App\StoreFeature;
use App\NotificationList;
use Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Twilio\Rest\Client;

use Illuminate\Support\Facades\Mail;


class IssueController extends Controller
{
    //
	public function manage(Request $request) {
		
		
		$result = [];
		$query = Issue::query();
		if($request->has('reportedIssue')) {
			$query->where('issues.reported_issue',$request->input('reportedIssue'));
		}
		
		if($request->has('diagnosedIssue')) {
			$query->whereRaw('FIND_IN_SET(?,issues.diagnosed_issue)', [$request->input('diagnosedIssue')]);
		}
		
		if($request->has('location')) {
			$query->where('issues.location',$request->input('location'));
		}
		
		if($request->has('feature')) {
			$query->where('issues.feature',$request->input('feature'));
		}
		
		if ($request->has('startDate')) {
			$query->where('issues.date_closed', '>=',
				Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00'))
				->where('issues.date_closed', '<=', Carbon::createFromFormat('Y-m-d H:i:s',
					$request->input('endDate') . ' 00:00:00')->addDay());
		}
		
		if($request->has('status')) {
			$query->where('issues.status',$request->input('status'));
		}
		
		$total    = $query->count();
		$pageSize = 10;
		$offset   = $request->input('offset');
 		$issues = $query->orderBy('id', 'desc')->skip($offset)->take($pageSize)->get();
		
		$issueItems = IssueItem::all();
		$reorderItems = [];
		foreach($issueItems as $issueItem ) {
			$reorderItems[$issueItem->id] = $issueItem;
		}
		
		foreach($issues as $issue) {
			$issue->reported_issue_text = $reorderItems[$issue->reported_issue]->name;
			$diagnosedIssues = explode(',',$issue->diagnosed_issue );
			$diagnosedIssuesString = '';
			foreach($diagnosedIssues as $diagnosedIssue) {
				if(is_numeric($diagnosedIssue)) {
					$diagnosedIssuesString =  $diagnosedIssuesString.$reorderItems[$diagnosedIssue]->name.' ,';
				}
			}
			$issue->diagnosed_issue = rtrim($diagnosedIssuesString,',');
			//$issue->location = 'TM #'.$issue->location;
		}
		
		
		foreach ($issues as $issue) {
			$result[] = [
				'closedAt' => $issue->date_closed,
				'status' => $issue->status,
				'reportedIssue' => $issue->reported_issue,
				'diagnosedIssues' => $issue->diagnosed_issue,
				'description' => $issue->description,
				'feature' => $issue->feature
			
			];
		}
		
		return response()->json([
			'total'    => $total,
			'totalNotFiltered' => $total,
			'pageSize' => $pageSize,
			'rows'   => $result,
		]);
	}


	public function createIssue(Request $request){
		
		$this->validate($request, [
			'reportedIssue' => 'required',
			'location' => 'required|numeric',
			'feature' => 'required|string'
		]);
		
		$newIssue = new Issue();
		$newIssue->status = 'reported';
		$newIssue->reported_issue = $request->input('reportedIssue');
		$newIssue->description = $request->input('description');
		$newIssue->location = $request->input('location');
		$newIssue->feature = $request->input('feature');
		$result =  $newIssue->save();
		$status = 'ok';
		
		if(!$result) {
			$status = 'fail';
		} else {
			$storeLocation = StoreLocation::find($newIssue->location);
			if(isset($storeLocation)) {
				if($storeLocation->email != null) {
					//$this->sendIssueStatusEmail('none',$newIssue,$storeLocation->email);
				}
				if($storeLocation->mobile != null) {
					//$this->sendIssueStatusMsg('none',$newIssue,$storeLocation->mobile);
				}
			}
		}
		return response()->json([
			'status' => $status,
			'item' => $request->all(),
		]);
	}


	

	public function updateIssue(Request $request, $id)
	{
		
		try {
			// find user
			$issue = Issue::findOrFail($id);
			$originStatus = $issue->status;
			$result =  $issue->update($request->all());
			$status = 'ok';
			if(!$result) {
				$status = 'fail';
			} else {
				$storeLocation = StoreLocation::find($issue->location);
				if(isset($storeLocation)) {
					if($storeLocation->email != null) {
						//$this->sendIssueStatusEmail($originStatus,$issue,$storeLocation->email);
					}
					if($storeLocation->mobile != null) {
						//$this->sendIssueStatusMsg($originStatus,$issue,$storeLocation->mobile);
					}
				}
				
			}
			
			return response()->json([
				'status' => $status,
				'item' => $request->all(),
			]);
		}
		catch (ModelNotFoundException $e)
		{
			// return custom response
			return response()->json([
				'status' => 'fail',
				'msg' => 'issue not found'
			]);
		}



	}
	
	public function getIssueByLocation($location) {
		$issueList =  Issue::where('location', intval($location))->where('status', "reported")->get();
		
		$issueItems = IssueItem::all();
		$reorderItems = [];
		foreach($issueItems as $issueItem ) {
			$reorderItems[$issueItem->id] = $issueItem;
		}
		
		foreach($issueList as $issue) {
			$issue->reported_issue_text = $reorderItems[$issue->reported_issue]->name;
			$diagnosedIssues = explode(',',$issue->diagnosed_issue );
			$diagnosedIssuesString = '';
			foreach($diagnosedIssues as $diagnosedIssue) {
				if(is_numeric($diagnosedIssue)) {
					$diagnosedIssuesString =  $diagnosedIssuesString.$reorderItems[$diagnosedIssue]->name.' ,';
				}
			}
			$issue->diagnosed_issue = rtrim($diagnosedIssuesString,',');
			//$issue->location = 'TM #'.$issue->location;
		}
		
		return response()->json([
			'status' => 'ok',
			'list' => $issueList
		]);
	
	}
	
	public  function getAllOpenIssues() {
		$storeCount = DB::table('issues')
				->select(DB::raw('count(*) as issueCount, location'))
						->groupBy('location')->get();
		$issueList = Issue::where('status', "reported")->get();

		$reorderItems = [];
		$issueItems = IssueItem::all();
		foreach($issueItems as $issueItem ) {
			$reorderItems[$issueItem->id] = $issueItem;
		}

		foreach($issueList as $issue) {
			$issue->reported_issue_text = $reorderItems[$issue->reported_issue]->name;
			$diagnosedIssues = explode(',',$issue->diagnosed_issue );
			$diagnosedIssuesString = '';
			foreach($diagnosedIssues as $diagnosedIssue) {
				if(is_numeric($diagnosedIssue)) {
					$diagnosedIssuesString =  $diagnosedIssuesString.$reorderItems[$diagnosedIssue]->name.' ,';
				}
			}
			$issue->diagnosed_issue = rtrim($diagnosedIssuesString,',');
			//$issue->location = 'TM #'.$issue->location;
		}
		
		return response()->json([
			'status' => 'ok',
			'list' => $issueList,
			'count' => $storeCount,
		]);
	}
	
	public function getIssueItems() {
		$issueItems = IssueItem::all();
		return response()->json([
			'status' => 'ok',
			'issueItems' => $issueItems,
		]);
	}
	
	public function getAllLocations() {
		$locations = StoreLocation::all();
		return response()->json([
			'status' => 'ok',
			'locations' => $locations,
		]);
	}
	
	public function getAllStoreFeature() {
		$storeFeatures = StoreFeature::all();
		return response()->json([
			'status' => 'ok',
			'storeFeatures' => $storeFeatures,
		]);
	}
	
	public function getAllFilterItems() {
		$issueItems = IssueItem::all();
		$locations = StoreLocation::all();
		$storeFeatures = StoreFeature::all();
		return response()->json([
			'status' => 'ok',
			'issueItems' => $issueItems,
			'locations' => $locations,
			'storeFeatures' => $storeFeatures,
		]);
	}
	
	
	public function exportDataToFile(Request $request){
		
		$query = Issue::query();
		if($request->has('reportedIssue')) {
			$query->where('issues.reported_issue',$request->input('reportedIssue'));
		}
		
		if($request->has('diagnosedIssue')) {
			$query->whereRaw('FIND_IN_SET(?,issues.diagnosed_issue)', [$request->input('diagnosedIssue')]);
		}
		
		if($request->has('location')) {
			$query->where('issues.location',$request->input('location'));
		}
		
		if($request->has('feature')) {
			$query->where('issues.feature',$request->input('feature'));
		}
		
		if ($request->has('startDate')) {
			$query->where('issues.date_closed', '>=',
				Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00'))
				->where('issues.date_closed', '<=', Carbon::createFromFormat('Y-m-d H:i:s',
					$request->input('endDate') . ' 00:00:00')->addDay());
		}
		
		if($request->has('status')) {
			$query->where('issues.status',$request->input('status'));
			
		}
		
		$issueItems = IssueItem::all();
		$reorderItems = [];
		foreach($issueItems as $issueItem ) {
			$reorderItems[$issueItem->id] = $issueItem;
		}
		
		$issues = $query->orderBy('id', 'desc')->get();
		foreach($issues as $issue) {
			$issue->reported_issue_text = $reorderItems[$issue->reported_issue]->name;
			$diagnosedIssues = explode(',',$issue->diagnosed_issue );
			$diagnosedIssuesString = '';
			foreach($diagnosedIssues as $diagnosedIssue) {
				if(is_numeric($diagnosedIssue)) {
					$diagnosedIssuesString =  $diagnosedIssuesString.$reorderItems[$diagnosedIssue]->name.' ,';
				}
			}
			$issue->diagnosed_issue = rtrim($diagnosedIssuesString,',');
			//$issue->location = 'TM #'.$issue->location;
		}
	
		
		$headers = array(
			"Content-type" => "text/csv",
			"Content-Disposition" => "attachment; filename=dataExport.csv",
			"Pragma" => "no-cache",
			"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
			"Expires" => "0"
		);
		

		$columns = array('Date Closed', 'Reported Issue', 'Diagnosed Issues', 'Location', 'Feature');
		
		$callback = function() use ($issues, $columns)
		{
			$file = fopen('php://output', 'w');
			fputcsv($file, $columns);
			
			foreach($issues as $issue) {
				fputcsv($file, array($issue->date_closed, $issue->reported_issue, $issue->diagnosed_issue, $issue->location, $issue->feature));
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
		
		
	}
	
	function sendIssueStatusEmail($originStatus, $issue, $address){
		
		
		$type = 'email';
		$fromStatus = $originStatus;
		$feature = $issue->feature;
		$location = $issue->location;
		$toStatus = $issue->status;
		$description = $issue->description;
		$toAddress = $address;
		
		$message = 'Location #'.$issue->location.' '.$issue->feature.' change status from '.$fromStatus.' to '.$toStatus.'. Description: '.$issue->description;
		$subject = 'Location #'.$issue->location.' '.$issue->feature;
		$result = Mail::send(
			'emails.notification',
			['content' => $message],
			function ($message) use($toAddress, $subject) {
				$message->to($toAddress)->subject($subject);
			}
		);
		if( count(Mail::failures()) == 0 ) {
			//to do save to database
			$newMobileNotification =  new NotificationList();
			$newMobileNotification->type = $type;
			$newMobileNotification->feature = $feature;
			$newMobileNotification->location = $location;
			$newMobileNotification->from_status = $fromStatus;
			$newMobileNotification->to_status = $toStatus;
			$newMobileNotification->description = $description;
			$newMobileNotification->address = $toAddress;
			$newMobileNotification->save();
		}
	
	}
	
	function sendIssueStatusMsg($originStatus, $issue, $address){
		
		$type = 'message';
		$fromStatus = $originStatus;
		$feature = $issue->feature;
		$location = $issue->location;
		$toStatus = $issue->status;
		$description = $issue->description;
		$toAddress = $address;
		
		$client = new Client(config('site.twilio_acount_id'), config('site.twilio_token'));
		$result = $client->messages->create(
			$toAddress,
			array(
				'from' => config('site.twilio_number'),
				'body' => 'Location #'.$issue->location.' '.$issue->feature.' change status from '.$fromStatus.' to '.$toStatus.'. Description: '.$issue->description
			)
		);
		if(isset($result) && $result->errorCode == null) {
			//to do save to database
			$newMobileNotification =  new NotificationList();
			$newMobileNotification->type = $type;
			$newMobileNotification->feature = $feature;
			$newMobileNotification->location = $location;
			$newMobileNotification->from_status = $fromStatus;
			$newMobileNotification->to_status = $toStatus;
			$newMobileNotification->description = $description;
			$newMobileNotification->address = $toAddress;
			$newMobileNotification->save();
		}
	}
	
}
