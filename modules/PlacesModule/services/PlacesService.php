<?php

namespace modules\PlacesModule\services;

use Craft;
use craft\base\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class PlacesService extends Component
{
    private $logger;

    public function __construct(LoggerInterface $logger, $config = [])
    {
        parent::__construct($config);
        $this->logger = $logger;
    }

    /**
     * Get Google Places API key from environment variable.
     *
     * @return string|null
     */
    private function getApiKey()
    {
        return getenv('GOOGLE_PLACES_API_KEY');
    }

    /**
     * Log an error message.
     *
     * @param string $message
     * @return void
     */
    private function logError(string $message)
    {
        $this->logger->error($message);
    }

    /**
     * Get place information by its ID from Google Places API.
     *
     * @param string $placeId
     * @return array|null
     */
    public function getPlaceInfoById(string $placeId)
    {
        $apiKey = $this->getApiKey();
        if (!$apiKey) {
            $this->logError('Google Places API key is missing.');
            return null;
        }

        try {
            $client = new Client();
            $response = $client->get("https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&key=$apiKey");
            $data = json_decode($response->getBody(), true);

            if (isset($data['result'])) {
                return [
                    'name' => $data['result']['name'],
                    'address' => $data['result']['formatted_address'],
                    'rating' => $data['result']['rating'],
                    'photo_url' => $this->getPhotoUrl($data['result']['photo_reference']),
                ];
            }

            return null;
        } catch (RequestException $e) {
            $this->logError('Error occurred while getting place info: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Search for places based on a partial query term using Google Places API.
     *
     * @param string $query
     * @return array|null
     */
    public function searchPlaces(string $query)
    {
        // Implement searchPlaces method here
    }

    /**
     * Get photo URL for a place based on its photo reference from Google Places API.
     *
     * @param string $photoReference
     * @return string|null
     */
    public function getPhotoUrl(string $photoReference)
    {
        // Implement getPhotoUrl method here
    }
}