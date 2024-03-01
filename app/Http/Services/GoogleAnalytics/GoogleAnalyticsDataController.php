<?php
namespace DDD\Http\Services\GoogleAnalytics;

use Illuminate\Http\Request;
use DDD\Http\Services\GoogleAnalytics\Requests\PageViewsRequest;
use DDD\Domain\Connections\Connection;
use DDD\App\Facades\GoogleAnalytics\GoogleAnalyticsData;
use DDD\App\Controllers\Controller;

class GoogleAnalyticsDataController extends Controller
{
    public function fetchUsersByPagePath(Connection $connection, PageViewsRequest $request)
    {   
        $report = GoogleAnalyticsData::fetchUsersByPagePath(
            connection: $connection, 
            startDate: $request->startDate,
            endDate: $request->endDate,
            pagePaths: $request->pagePaths,
        );

        return response()->json([
            'data' => $report
        ], 200);
    }

    public function fetchOutboundClicks(Connection $connection, Request $request)
    {
        $report = GoogleAnalyticsData::fetchOutboundClicks(
            connection: $connection, 
            startDate: $request->startDate, 
            endDate: $request->endDate,
            outboundLinkUrls: $request->outboundLinkUrls,
        );

        return response()->json([
            'data' => $report
        ], 200);
    }

    public function fetchOutboundClicksByPagePath(Connection $connection, Request $request)
    {
        $report = GoogleAnalyticsData::fetchOutboundClicksByPagePath(
            connection: $connection, 
            startDate: $request->startDate, 
            endDate: $request->endDate,
            outboundLinkUrls: $request->outboundLinkUrls,
            pagePath: $request->pagePath,
        );

        return response()->json([
            'data' => $report
        ], 200);
    }

    // public function runReport(Connection $connection, Request $request)
    // {   
    //     $report = GoogleAnalyticsData::runReport($connection, $request);

    //     return response()->json([
    //         'data' => $report
    //     ], 200);
    // }

    // public function runFunnelReport(Connection $connection)
    // {   
    //     $report = GoogleAnalyticsData::runFunnelReport($connection);

    //     return response()->json([
    //         'data' => $report
    //     ], 200);
    // }
}
