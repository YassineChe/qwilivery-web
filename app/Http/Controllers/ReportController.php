<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    //* Fetch Report
    public function fetchReports(){
        try{
            return response(Report::all(), 200);
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
            return dataToResponse('success', 'Succès ', 'votre rapport envoyé, nous vous contacterons dans les plus brefs délais', false, 200);
        }
        catch(\Exception $e){
            handleLogs($e);
        }
    }

    public function deleteReport($report_id){
        try{
            if(Report::where('id', (int)$report_id)->delete())
                return dataToResponse('success', 'Succès ', 'Rapport supprimé ❌', false, 200); 
        }catch(\Exception $e){
            handleLogs($e);
        }
    }

}
