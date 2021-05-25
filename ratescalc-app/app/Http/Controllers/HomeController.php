<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
	public function home(Request $request)
	{
		$page = $request->get('page', 1);
		$sortDirection = $request->get('sort', 'desc');
		$apiKey = config('app.API_KEY');

		$response = Http::get('https://api.themoviedb.org/3/movie/popular?api_key=' . $apiKey . '&page=' . $page);

		$json = $response->json();
		$status = $response->status();

		if ($status == 200 && count($json['results']) > 0)
		{
			$pages = $json['total_pages'];
			$movies = $json['results'];

			if ($sortDirection == 'desc')
			{
				usort($movies, function ($item1, $item2) {
					return $item2['popularity'] <=> $item1['popularity'];
				});
			}
			else
			{
				usort($movies, function ($item1, $item2) {
					return $item1['popularity'] <=> $item2['popularity'];
				});
			}
		}
		else
		{
			$pages = false;
			$movies = false;
		}

		return view('home', ['movies' => $movies, 'pages' => $pages, 'page' => $page, 'sortDirection' => $sortDirection]);
	}
}
