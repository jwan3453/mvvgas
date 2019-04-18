<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Issue;
use App\IssueItem;
use App\Location;
use App\StoreFeature;
use Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;


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
			$issue->reported_issue = $reorderItems[$issue->reported_issue]->name;
			$diagnosedIssues = explode(',',$issue->diagnosed_issue );
			$diagnosedIssuesString = '';
			foreach($diagnosedIssues as $diagnosedIssue) {
				if(is_numeric($diagnosedIssue)) {
					$diagnosedIssuesString =  $diagnosedIssuesString.$reorderItems[$diagnosedIssue]->name.' ,';
				}
			}
			$issue->diagnosed_issue = rtrim($diagnosedIssuesString,',');
			$issue->location = 'Tacoma Express #'.$issue->location;
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
//	public function show($id)
//	{
//		return Issue::find($id);
//	}
//	public function store(Request $request) {
//		return Issue::create($request->all());
//	}



	public function createIssue(Request $request){
		
		$this->validate($request, [
			'reportedIssue' => 'required|string',
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
			$result =  $issue->update($request->all());
			$status = 'ok';
			if(!$result) {
				$status = 'fail';
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
		$locations = Location::all();
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
		$locations = Location::all();
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
			$issue->reported_issue = $reorderItems[$issue->reported_issue]->name;
			$diagnosedIssues = explode(',',$issue->diagnosed_issue );
			$diagnosedIssuesString = '';
			foreach($diagnosedIssues as $diagnosedIssue) {
				if(is_numeric($diagnosedIssue)) {
					$diagnosedIssuesString =  $diagnosedIssuesString.$reorderItems[$diagnosedIssue]->name.' ,';
				}
			}
			$issue->diagnosed_issue = rtrim($diagnosedIssuesString,',');
			$issue->location = 'Tacoma Express #'.$issue->location;
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
}
