<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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

    public function getAccessToken()
    {
        try {
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
                return null;
            }
        } catch (GuzzleException $exception) {
            \Log::error('Get access token error: ' . print_r($exception->getMessage(), true));
            return abort($exception->getCode(), $exception->getMessage());
        }
    }

    public function getJobTitles()
    {
        try {
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
        } catch (GuzzleException $exception) {
            \Log::error('Get job titles error: ' . print_r($exception->getMessage(), true));
            return abort($exception->getCode(), $exception->getMessage());
        }
    }

    public function showEstimationForm()
    {
        $jobs = $this->getJobTitles();

        if (is_array($jobs)) {
            return view('estimation')->with('jobs', $jobs);
        } else {
            throw new \Exception('Unable to get job titles');
        }
    }

    public function getEstimationQuote(Request $request)
    {
        \Log::info("Request " . print_r($request->all(), true));
        try {
            $response = $this->client->request('POST', $this->version . '/estimator/car/predict', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
                'form_params' => $request->except(['_token']),
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            \Log::info("Quote Response " . print_r($result, true));

            if ($response->getStatusCode() === 200) {
                return response()->json([
                    'success' => true,
                    'data'    => $result['data'],
                ], 200);
            } else {
                throw new \Exception('Unable to get quote');
            }
        } catch (GuzzleException $exception) {
            \Log::error('Get Estimation Error: ' . print_r($exception->getMessage(), true));
            $response = $exception->getResponse();
            $result = json_decode($response->getBody()->getContents(), true);

            $errors = [];
            if (isset($result['errors'])) {
                foreach ($result['errors'] as $key => $value) {
                    $errors[] = $value[0]['error'];
                }
            }

            return response()->json([
                'success' => false,
                'data' => $errors,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
