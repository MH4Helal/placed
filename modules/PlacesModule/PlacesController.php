<?php

namespace modules\PlacesModule\controllers;

use Craft;
use craft\web\Controller;
use yii\web\Response;
use modules\PlacesModule\services\PlacesService;

class PlaceController extends Controller
{
    /**
     * Get place information by its ID from Google Places API.
     *
     * @param string $placeId
     * @return Response
     */
    public function actionGetPlaceInfoById(string $placeId): Response
    {
        $placeService = Craft::$app->get('places');
        $placeInfo = $placeService->getPlaceInfoById($placeId);

        return $this->asJson($placeInfo);
    }

    /**
     * Search for places based on a partial query term using Google Places API.
     *
     * @return Response
     */
    public function actionSearchPlaces(): Response
    {
        $query = Craft::$app->getRequest()->getQueryParam('query');
        $placeService = Craft::$app->get('places');
        $searchResults = $placeService->searchPlaces($query);

        return $this->asJson($searchResults);
    }
}