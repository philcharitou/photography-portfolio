<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class BugReportController extends Controller
{
    /**
     * Create a new controller instance.
     * Methods within this instance can only be accessed by users who are:
     * A) authenticated,
     * B) have the role "super_admin"
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display all bug reports
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $reports = BugReport::all();

        return view('system.bug-reports.index')
            ->with('reports', $reports);
    }

    public function getFailedJob()
    {
        #Fetch all the failed jobs
        $jobs = DB::table('failed_jobs')->get();

        #Loop through all the failed jobs and format them for json printing
        foreach ($jobs as $job) {
            $jsonpayload = json_decode($job->payload);
            $job->payload = $jsonpayload;

            $jsonpayload->data->command = unserialize($jsonpayload->data->command);
            $job->exception  = explode("\n", $job->exception);
        }

        return response()->json($jobs);
    }

    public function show($id)
    {
    }

    public function create()
    {
    }

    /**
     * Store a newly created bug report in storage
     */
    public function store(Request $request)
    {
    }

    /**
     * AJAX Store a newly created bug report in storage
     *
     */
    public function ajax_store(Request $request)
    {
        $bug = BugReport::create([
            'title' => $request["title"],
            'body' => $request["body"],
            'author' => auth()->user()->email,
            'user_id' => auth()->user()->id,
        ]);

        $users = User::role('super_admin')->get();
        //Notification::send($users, new BugReported($bug));
    }

    public function edit($id)
    {
    }

    public function update()
    {
    }

    /**
     * Delete the selected bug report
     *
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        BugReport::find($id)?->delete();

        return redirect()->route('bug-reports.index')
            ->with('swal_delete', '');
    }

    public function delete_all()
    {
        BugReport::truncate();

        return redirect()->route('bug-reports.index')
            ->with('swal_delete', '');    }
}
