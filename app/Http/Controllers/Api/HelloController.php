<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HelloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $visitor_name = $request->visitor_name;
        $client_ip = $request->ip();
     
        $locationInfo = Http::get('https://ipapi.co/' . $client_ip . '/json/')->json();
        $city = $locationInfo['city'] ?? 'New York';
        $weatherInfo = Http::get("http://api.weatherapi.com/v1/current.json?key=f9b082fd88974d749d585442240207&q={$city}")->json();
        $temperature = $weatherInfo['current']['temp_c'] ?? '11';
        $greeting = "Hello, {$visitor_name}  The temperature is  {$temperature}  degrees Celsius in {$city }";
        return response()->json([
            'client_ip'=>$client_ip,
            'location'=>$city,
            'greeting'=>$greeting,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
