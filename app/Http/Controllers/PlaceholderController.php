<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaceholderController extends Controller
{
    /**
     * Assessment placeholder page.
     */
    public function assessment()
    {
        return view('assessment.index');
    }

    /**
     * IT Risk & Control placeholder page.
     */
    public function itRiskControl()
    {
        return view('it-risk-control.index');
    }

    /**
     * Monitoring placeholder page.
     */
    public function monitoring()
    {
        return view('monitoring.index');
    }

    /**
     * Evidence placeholder page.
     */
    public function evidence()
    {
        return view('evidence.index');
    }

    /**
     * Master Data placeholder page.
     */
    public function masterData()
    {
        return view('master-data.index');
    }

    /**
     * Activity Log placeholder page.
     */
    public function activityLog()
    {
        return view('activity-log.index');
    }

    /**
     * Profile placeholder page.
     */
    public function profile()
    {
        return view('profile.index');
    }
}
