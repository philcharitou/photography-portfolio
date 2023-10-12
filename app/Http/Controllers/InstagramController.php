<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use InstagramScraper\Instagram;
use Phpfastcache\Helper\Psr16Adapter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class InstagramController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function get_posts()
    {
        $array = [];

        for($i = 0; $i < 1; $i++) {
            $src = Redis::get("instagram_post[".$i."]");
            $path = public_path("instagram_" . $i . ".webp");

            if($src) {
                if (file_put_contents($path, file_get_contents($src)))
                {
                    $array[] = $path;
                }
            } else {
                $client = new \GuzzleHttp\Client();

                $response = $client->request('GET', 'https://instagram130.p.rapidapi.com/account-medias?userid=62028712661&first=40', [
                    'headers' => [
                        'X-RapidAPI-Host' => 'instagram130.p.rapidapi.com',
                        'X-RapidAPI-Key' => 'fd2ce13fbdmsh198f9058dd00420p1ef486jsneeb1b98583bb',
                    ],
                ]);

                $content = json_decode($response->getBody());

                if(array_key_exists($i, $content->edges)) {
                    if(file_exists('your_file_name')){
                        unlink($path);
                    }

                    $node = $content->edges[$i];

                    $src = $node->node->display_resources[0]->src;

                    if (file_put_contents($path, file_get_contents($src)))
                    {
                        Redis::set("instagram_post[".$i."]", $path, 'EX', now()->diffInSeconds(now()->addDay()));

                        $array[] = $path;
                    }
                }

            }

        }

        return $array;
    }
}
