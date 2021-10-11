<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class EstimationController extends Controller
{
    protected $client;
    protected $access_token;
    protected $version;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => "https://micro.quotezone.co.uk/api/",
            'verify'   => config('app.env') === 'production' ? true : false
        ]);

        $this->access_token = $this->getAccessToken();
        $this->version = config('app.env') === 'production' ? 'v1' : 'test';
    }

    public function getAccessToken() {
        $response = $this->client->request('POST', 'auth/login', [
            'form_params' => [
                'email' => 'dev.team@stoneacre.co.uk',
                'password' => 'zlw5OwEZ'
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            $result = json_decode($response->getBody()->getContents(), true);
            return $result['status'] === 'success' ? $result['data']['access_token'] : null;
        } else {
            throw new \Exception('Unable to get access token');
        }
    }

    public function getJobTitles() {
        $response = $this->client->request('POST', $this->version . '/partner/jobTitles', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->access_token,
            ],
            'form_params' => ['jobTitle' => '???'],
        ]);
        if ($response->getStatusCode() === 200) {
            $result = json_decode($response->getBody()->getContents(), true);
            return $result['status'] === 'success' ? $result['data'] : null;
        } else {
            return null;
        }
    }

    public function showEstimationForm()
    {
        $jobs = $this->getJobTitles();

        if ($jobs) {
            return view('estimation')->with('jobs', $jobs);
        } else {
            throw new \Exception('Unable to get job titles');
        }
    }

    public function getEstimationQuote(Request $request)
    {
        \Log::info("Request " . print_r($request->all(), true));
        $response = $this->client->request('POST', $this->version . '/estimator/car/predict', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->access_token,
            ],
            'form_params' => $request->except(['_token']),
        ]);
        $statusCode = $response->getStatusCode();
        $result = json_decode($response->getBody()->getContents(), true);
        \Log::info("Quote Response " . print_r($result, true));
        return response()->json($result, $statusCode);
    }
}
