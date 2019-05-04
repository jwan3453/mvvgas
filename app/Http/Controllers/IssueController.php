<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Issue;
use App\IssueItem;
use App\StoreLocation;
use App\StoreFeature;
use App\NotificationList;
use Illuminate\Session\Store;
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
		$order = $request->input('order');
		$order = isset($order)?$order:'asc';
 		$issues = $query->skip($offset)->take($pageSize)->get();
		$issueItems = IssueItem::all();
		$locations = StoreLocation::all();
		
		if($order == 'asc') {
			$issues = $issues->sort(function ($a, $b) {
				if ($a->date_closed == $b->date_closed) {
					return 0;
				}
				return ($a->date_closed < $b->date_closed) ? -1 : 1;
			});
		} else {
			$issues = $issues->sort(function ($a, $b) {
				if ($a->date_closed == $b->date_closed) {
					return 0;
				}
				return ($a->date_closed > $b->date_closed) ? -1 : 1;
			});
		}
		
		$reorderLocations = [];
		foreach($locations as $locationItem ) {
			$reorderLocations[$locationItem->id] = $locationItem;
		}
		
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
			$issue->location_text = $reorderLocations[$issue->location]['name'];
		}
		
		
		foreach ($issues as $issue) {
			$result[] = [
				'closedAt' => $issue->date_closed,
				'status' => $issue->status,
				'reportedIssue' => $issue->reported_issue,
				'reportedIssueText' => $issue->reported_issue_text,
				'diagnosedIssues' => $issue->diagnosed_issue,
				'locationText' => $issue->location_text,
				'description' => $issue->description,
				'location' => $issue->location,
				'feature' => $issue->feature
			
			];
		}
		
		return response()->json([
			'status' => 'ok',
			'total'    => $total,
			'totalNotFiltered' => $total,
			'pageSize' => $pageSize,
			'rows'   => $result,
		]);
	}
	
	public function getIssueByLocation($location) {
		$issueList =  Issue::where('location', intval($location))->whereIn('status', ["reported","on hold"])->get();
		
		$issueItems = IssueItem::all();
		$locations = StoreLocation::all();
		
		
		$reorderItems = [];
		foreach($issueItems as $issueItem ) {
			$reorderItems[$issueItem->id] = $issueItem;
		}
		
		$reorderLocations = [];
		foreach($locations as $locationItem ) {
			$reorderLocations[$locationItem->id] = $locationItem;
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
			$issue->location_text = $reorderLocations[$issue->location]['name'];
		}
		
		return response()->json([
			'status' => 'ok',
			'list' => $issueList
		]);
		
	}
	
	public  function getAllOpenIssues() {
		$storeCount = DB::table('issues')
			->select(DB::raw('count(*) as issueCount, location'))
			->whereIn('status', ["reported","on hold"])
			->groupBy('location')->get();
		$issueList = Issue::whereIn('status', ["reported","on hold"])->get();
		
		$locations = StoreLocation::all();
		$reorderLocations = [];
		foreach($locations as $locationItem ) {
			$reorderLocations[$locationItem->id] = $locationItem;
		}
		
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
			$issue->location_text = $reorderLocations[$issue->location]['name'];
		}
		
		return response()->json([
			'status' => 'ok',
			'list' => $issueList,
			'count' => $storeCount,
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
					$this->sendIssueStatusEmail('none',$newIssue,$storeLocation);
				}
				if($storeLocation->mobile != null) {
					$this->sendIssueStatusMsg('none',$newIssue,$storeLocation);
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
			$issue->status = $request->input('status');
			$issue->diagnosed_issue = $request->input('diagnosedIssue');
			$issue->description = $request->input('description');
			if($issue->status == 'closed') {
				$issue->date_closed =Carbon::now();
			}
			$result = $issue->save();
			$status = 'ok';
			if(!$result) {
				$status = 'fail';
			} else {
				$storeLocation = StoreLocation::find($issue->location);
				if(isset($storeLocation)) {
					if($storeLocation->email != null) {
						$this->sendIssueStatusEmail($originStatus,$issue,$storeLocation);
					}
					if($storeLocation->mobile != null) {
						$this->sendIssueStatusMsg($originStatus,$issue,$storeLocation);
					}
				}
				
			}
			
			return response()->json([
				'status' => $status,
				'item' => $issue,
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
			'features' => $storeFeatures,
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
		
		$locations = StoreLocation::all();
		$reorderLocations = [];
		foreach($locations as $locationItem ) {
			$reorderLocations[$locationItem->id] = $locationItem;
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
			$issue->location_text = $reorderLocations[$issue->location]['name'];
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
				fputcsv($file, array($issue->date_closed, $issue->reported_issue, $issue->diagnosed_issue, $issue->location_text, $issue->feature));
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
		
		
	}
	
	function sendIssueStatusEmail($originStatus, $issue, $location){
		
		
		$type = 'email';
		$fromStatus = $originStatus;
		$feature = $issue->feature;
		$toStatus = $issue->status;
		$description = $issue->description;
		$manager = $location->manager;
		$toAddress = $location->email;
		$messageBody = '';
		
		$client = new Client(config('site.twilio_acount_id'), config('site.twilio_token'));
		if($toStatus == 'reported' || $toStatus == 'on hold' ) {
			$subject = 'Issue Reported';
			$messageBody = 'Hi '.$manager. ', there is a new incident report for '.$feature.' available at '.config('site.server').'/adminlocationview/'.$location->id;
		} else {
			$subject = 'Issue Closed';
			$messageBody = 'Hi '.$manager. ', an incident for '.$feature.' was recently closed. To view this issue please go to '.config('site.server').'/closedissue';
		}
		
		
		$result = Mail::send(
			'emails.notification',
			['content' => $messageBody],
			function ($message) use($toAddress, $subject) {
				$message->to($toAddress)->subject($subject);
			}
		);
		if( count(Mail::failures()) == 0 ) {
			//to do save to database
			$newMobileNotification =  new NotificationList();
			$newMobileNotification->type = $type;
			$newMobileNotification->feature = $feature;
			$newMobileNotification->location = $location->id;
			$newMobileNotification->from_status = $fromStatus;
			$newMobileNotification->to_status = $toStatus;
			$newMobileNotification->description = $description;
			$newMobileNotification->address = $toAddress;
			$newMobileNotification->save();
		}
	
	}
	
	function sendIssueStatusMsg($originStatus, $issue, $location){
		
		$type = 'message';
		$fromStatus = $originStatus;
		$feature = $issue->feature;
		$toStatus = $issue->status;
		$description = $issue->description;
		$manager = $location->manager;
		$toAddress = $location->mobile;
		$messageBody  = '';
		$client = new Client(config('site.twilio_acount_id'), config('site.twilio_token'));
		if($toStatus == 'reported' || $toStatus == 'on hold' ) {
			$messageBody = 'Hi '.$manager. ', there is a new incident report for '.$feature.' available at '.config('site.server').'/adminlocationview/'.$location->id;
		} else {
			$messageBody = 'Hi '.$manager. ', an incident for '.$feature.' was recently closed. To view this issue please go to '.config('site.server').'/closedissue';
		}
		
		
		$result = $client->messages->create(
			$toAddress,
			array(
				'from' => config('site.twilio_number'),
				'body' => $messageBody,
			)
		);
		if(isset($result) && $result->errorCode == null) {
			//to do save to database
			$newMobileNotification =  new NotificationList();
			$newMobileNotification->type = $type;
			$newMobileNotification->feature = $feature;
			$newMobileNotification->location = $location->id;
			$newMobileNotification->from_status = $fromStatus;
			$newMobileNotification->to_status = $toStatus;
			$newMobileNotification->description = $description;
			$newMobileNotification->address = $toAddress;
			$newMobileNotification->save();
		}
	}
	
}
