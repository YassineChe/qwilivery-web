<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    //* Fetch Report
    public function fetchReports(){
        try{

            //? In case thi function called its means
            //? That the admin visited report list
            //? So before dispatching rows let's update seen_at

            Report::whereNull('seen_at')->update(['seen_at' => \Carbon\Carbon::now()]);

            return response()->json([
                'deliveries' => Report::where('guard', 'delivery')->with('delivery')->orderBy('id', 'DESC')->get(),
                'restaurants'=> Report::where('guard', 'restaurant')->with('restaurant')->orderBy('id', 'DESC')->get()
            ]);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    //* Add Report
    public function addReport(Request $request){
        try{
            if(
                Report::create([
                    'guard'       => getConnectedGuard(),
                    'guard_id'    => authIdFromGuard(getConnectedGuard()),
                    'subject'     => $request->subject,
                    'description' => $request->description
                ])
            )
            return dataToResponse('success', 'Succès ', ['Votre rapport envoyé, nous vous contacterons dans les plus brefs délais'], 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    public function deleteReport($report_id){
        try{
            if(Report::where('id', (int)$report_id)->delete())
                return dataToResponse('success', 'Succès ', ['Rapport supprimé ❌'], 200); 
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

}
