<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Setting;
use Elfcms\Elfcms\Models\VisitStatistic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VisitStatisticController extends Controller
{
    public function index(Request $request)
    {
        $useStatistic = Setting::value('site_statistics_use');
        if (empty($useStatistic) || $useStatistic != 1) {
            return view('elfcms::admin.statistics.off',[
                'page' => [
                    'title' => 'Statistics',
                    'current' => url()->current(),
                    'keywords' => '',
                    'description' => ''
                ],
            ]);
        }

        $trend_f = 'desc';
        $order_f = 'created_at';
        if (!empty($request->trend_f) && $request->trend_f == 'desc') {
            $trend_f = 'desc';
        }
        if (!empty($request->order_f)) {
            $order_f = $request->order_f;
        }
        if (!empty($request->count_f)) {
            $count_f = intval($request->count_f);
        }
        if (empty($count_f)) {
            $count_f = 30;
        }


        $statistics = VisitStatistic::orderBy($order_f, $trend_f)->paginate($count_f)->withQueryString();
        $trend_u = 'desc';
        $order_u = 'created_at';
        if (!empty($request->trend_u) && $request->trend_u == 'desc') {
            $trend_u = 'desc';
        }
        if (!empty($request->order_u)) {
            $order_u = $request->order_u;
        }
        if (!empty($request->count_u)) {
            $count_u = intval($request->count_u);
        }
        if (empty($count_u)) {
            $count_u = 30;
        }
        if (!empty($request->page_u)) {
            $page_u = $request->page_u;
        }
        if (empty($page_u)) {
            $page_u = 1;
        }
        $uniqueVisitsCollection = VisitStatistic::orderBy($order_u, $trend_u)->get()->unique(function($item){
            return $item['ip'].$item['agent'].$item['uri'].$item['user_id'].$item['tmp_user_uuid'].$item['method'];
        });

        $uvCount = $uniqueVisitsCollection->count();
        $uvPageCount = intval(ceil($uvCount/$count_u));

        $uniqueVisitsCollectionChunks = $uniqueVisitsCollection->chunk($count_u);

        if ($page_u>$uvPageCount) {
            $page_u = $uvPageCount;
        }

        if (!empty($uvCount)) {
            $uniqueVisits = $uniqueVisitsCollectionChunks[$page_u-1];
        }
        else {
            $uniqueVisits = [];
        }

        //dd(intval(ceil($uvCount/$count_u)));

        //dd($uniqueVisits->count());
        return view('elfcms::admin.statistics.index',[
            'page' => [
                'title' => 'Statistics',
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'statistics' => $statistics,
            'uniqueVisits' => $uniqueVisits,
            'uvPageCount' => $uvPageCount,
            'uvPage' => $page_u
        ]);
    }
}
