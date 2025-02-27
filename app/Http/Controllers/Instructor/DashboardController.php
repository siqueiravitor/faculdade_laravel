<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\AffiliateHistory;
use App\Models\BookingHistory;
use App\Models\Bundle;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\Order_item;
use App\Models\RankingLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Google_Client;
use Google_Service_Drive;

class DashboardController extends Controller {
//    public function dashboard()
//    {
//        $fileId = "1MA7FYWxdKjK1--w_biC8JgQbuIsS3LZ6";
//        $client = new Google_Client();
//        $client->setAuthConfig('/videocontroller.json');
//        $client->addScope(Google_Service_Drive::DRIVE_READONLY);
//        $service = new Google_Service_Drive($client);
//
//        $file = $service->files->get($fileId, ['fields' => 'webViewLink']);
//        $videoUrl = $file->getWebViewLink();
//
//        return view('instructor.dashboard', compact('videoUrl'));
//    }
    public function dashboard() {
        $data['title'] = 'Dashboard';
        
        $applicationName = env('APP_NAME');
        $redirectUri = route('instructor.gmeet_callback');
        $clientId = "";
        $clientSecret = "";
        $client = new Google_Client();

        $client->setApplicationName($applicationName);
        $client->setRedirectUri($redirectUri);

        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
        $client->setHttpClient($guzzleClient);
        
        $credentialsPath = base_path('storage/videocontroller.json');
        $client->setAuthConfig($credentialsPath);
        $client->addScope(Google_Service_Drive::DRIVE_READONLY);

        $videoUrl = "";

        $cancelConsultationOrderItemIds = BookingHistory::where(['instructor_user_id' => Auth::id(), 'status' => BOOKING_HISTORY_STATUS_CANCELLED, 'send_back_money_status' => SEND_BACK_MONEY_STATUS_YES])
                        ->whereYear("created_at", now()->year)->whereMonth("created_at", now()->month)
                        ->whereHas('order', function ($q) {
                            $q->where('payment_status', 'paid');
                        })->pluck('order_item_id')->toArray();
        $consultationOrderItems = Order_item::whereIn('id', $cancelConsultationOrderItemIds);
        //End::  Cancel Consultation Money Calculation

        $userCourseIds = CourseInstructor::where('instructor_id', auth()->id())->select('course_id')->get()->pluck('course_id')->toArray();

        $orderBundleItemsCount = Order_item::where('owner_user_id', Auth::id())->whereNotNull('bundle_id')->whereNull('course_id')
                        ->whereYear("created_at", now()->year)->whereMonth("created_at", now()->month)
                        ->whereHas('order', function ($q) {
                            $q->where('payment_status', 'paid');
                        })->count();

        $orderItems = Order_item::whereIn('course_id', $userCourseIds)
                ->whereYear("created_at", now()->year)->whereMonth("created_at", now()->month)
                ->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        });
        //Start::Affiliate Earning from AffiliateHistory
        $affiliateHistoryTotalCommission = AffiliateHistory::where(['user_id' => Auth::id(), 'status' => AFFILIATE_HISTORY_STATUS_PAID])->sum('commission');
        //End::Affiliate Earning from AffiliateHistory

        $data['total_earning_this_month'] = $orderItems->sum('owner_balance') + $affiliateHistoryTotalCommission - $consultationOrderItems->sum('owner_balance');
        $data['total_enroll_this_month'] = $orderItems->count('id') - $orderBundleItemsCount - count($cancelConsultationOrderItemIds);

        $data['best_selling_course'] = Order_item::whereIn('course_id', $userCourseIds)->whereHas('order', function ($q) {
                    $q->where('payment_status', 'paid');
                })->groupBy("course_id")->selectRaw("COUNT(course_id) as total_course_id, course_id")->orderByRaw("COUNT(course_id) DESC")->first();

        $data['recentCourses'] = Course::whereIn('id', $userCourseIds)->latest()->limit(2)->get();
        $data['updatedCourses'] = Course::whereIn('id', $userCourseIds)->orderBy('updated_at', 'desc')->limit(2)->get();

        // Start:: Sale Statistics
        $months = collect([]);
        $totalPrice = collect([]);

        //Start::  Cancel Consultation Money Calculation
        $cancelConsultationOrderItemIds = BookingHistory::where(['instructor_user_id' => Auth::id(), 'status' => 2, 'send_back_money_status' => SEND_BACK_MONEY_STATUS_YES])
                        ->whereHas('order', function ($q) {
                            $q->where('payment_status', 'paid');
                        })->pluck('order_item_id')->toArray();
        //End
        Order_item::whereNotIn('id', $cancelConsultationOrderItemIds)->whereIn('course_id', $userCourseIds)->whereHas('order', function ($q) {
                    $q->where('payment_status', 'paid');
                })->select(DB::raw('SUM(owner_balance) as total'), DB::raw('MONTHNAME(created_at) month'))
                ->groupby('month')
                ->get()
                ->map(function ($q) use ($months, $totalPrice) {
                    $months->push($q->month);
                    $totalPrice->push($q->total);
                });

        $data['months'] = $months;
        $data['totalPrice'] = $totalPrice;
        //End:: Sale Statistics
        //Start:: ranking Level
        $allOrderItems = Order_item::whereIn('course_id', $userCourseIds)->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        });

        $data['grand_total_earning'] = $allOrderItems->sum('owner_balance');
        $data['grand_total_enroll'] = $allOrderItems->count('id') - $orderBundleItemsCount;

        $currentLevel = get_instructor_ranking_level(auth()->user()->badges);

        $data['rankingLevels'] = RankingLevel::orderBy('serial_no', 'DESC')->where('type', RANKING_LEVEL_EARNING)->get();
        $data['currentLevel'] = $data['rankingLevels']->where('name', $currentLevel)->first();
        $data['secondLevel'] = $data['rankingLevels']->where('id', '>', @$data['currentLevel']->id)->first();
        //End:: ranking Level

        $data['idVideoDrive'] = $this->buscarArquivosDrive('peb-rxyv-crw');
//        dd($data);
        return view('instructor.dashboard', array_merge($data, ['videoUrl' => $videoUrl]));
//        return view('instructor.dashboard', $data);

    }

    public function rankingLevelList() {
        $data['title'] = 'Dashboard';
        $data['navDashboardActiveClass'] = 'active';
        $data['levels'] = RankingLevel::orderBy('serial_no', 'asc')->where('type', RANKING_LEVEL_EARNING)->get();
        return view('instructor.ranking-badge-list', $data);
    }

    public function buscarArquivosDrive() {
        try {
            $applicationName = env('APP_NAME');
            $redirectUri = route('instructor.gmeet_callback');
            $clientId = get_option('gmeet_client_id');
            $clientSecret = get_option('gmeet_client_secret');
            $client = new \Google_Client();

            $client->setApplicationName($applicationName);
            $client->setRedirectUri($redirectUri);

            $client->setScopes(Google_Service_Drive::DRIVE);
            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
            $client->setHttpClient($guzzleClient);

            $authUrl = $client->createAuthUrl();
            return redirect($authUrl);
        } catch (Exception $e) {
            return $e;
        }
    }

    protected function getClientToken() {
        $applicationName = env('APP_NAME');
        $redirectUri = route('instructor.gmeet_callback');
        $clientId = "";
        $clientSecret = "";
        $client = new Google_Client();

        $client->setApplicationName($applicationName);
        $client->setRedirectUri($redirectUri);

        $client->setScopes(Google_Service_Drive::DRIVE);
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
        $client->setHttpClient($guzzleClient);

        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

}
