<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 1/10/15
 * Time: 11:28
 */

namespace Bakgat\Notos\Http\Controllers\Resource;


use Bakgat\Notos\Http\Controllers\Controller;
use DOMDocument;
use Illuminate\Http\Response;
use League\Flysystem\NotSupportedException;

class MealsController extends Controller
{
    public function index()
    {
        $dom = new DOMDocument();
        if (@$dom->loadHTMLFile(env('MEALS_URL', ''))) {
            $elements = $dom->getElementsByTagName('table');

            if ($elements->length > 0) {
                $response = '';
                foreach ($elements as $table) {
                    if ($table->attributes->getNamedItem('class') &&
                        str_contains($table->attributes->getNamedItem('class')->value, 'table')
                    ) {
                        //CLEAR IMAGES
                        $images  = $table->getElementsByTagName('img');
                        foreach ($images as $image) {
                            $image->parentNode->removeChild($image);
                        }

                        $response .= $dom->saveHtml($table);
                    }
                }

                return $response;
            }
        }
        return Response::create('', 404);
    }

    public function prices()
    {
        throw new NotSupportedException;
    }
}