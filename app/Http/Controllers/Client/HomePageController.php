<?php

namespace App\Http\Controllers\Client;

use App\Models\Plan;
use App\Models\About;
use App\Models\Slide;
use App\Models\Stack;
use App\Models\Topic;
use App\Models\Contact;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Depoiment;
use App\Models\PlanSection;
use App\Models\PlanCategory;
use Illuminate\Http\Request;
use App\Models\ProductSection;
use App\Models\DepoimentSession;
use App\Models\StackSessionTitle;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    public function index()
    {
        return view('client.blades.app');
    }

    public function getPlansByCategory($id)
    {
        $contact = Contact::first();
        $plans = Plan::select(
        'plans.plan_category',
        'plans.title',
        'plans.subtitle',
        'plans.bandwidth_limit',
        'plans.bandwidth_unit',
        'plans.description',
        'plans.price',
        'plans.active',
        'plans.sorting'
        )->where('plan_category', $id)
        ->active()->sorting()->get();

        $html = view('client.blades.ajax.plan', compact('plans', 'contact'))->render();

        return response()->json(['html' => $html]);
    }

}
