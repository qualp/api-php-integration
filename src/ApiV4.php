<?php

namespace Qualp\Api;

use Qualp\Api\Exceptions\V4\InvalidAxisException;
use Qualp\Api\Exceptions\V4\InvalidParamsException;
use Qualp\Api\Exceptions\V4\InvalidPolylineException;
use Qualp\Api\Support\Vehicles;

class ApiV4 extends Api
{
    protected string $url = "http://api.qualp.com.br";
    protected array $locations;
    protected array $polyline;
    protected string $vehicle;
    protected string $vehicleAxis;
    protected string $freightTableCategory;
    protected string $freightTableLoad;
    protected bool $shouldCalculateReturn = false;
    protected int $maxDistanceFromLocationToRoute = 1000;
    protected bool $shouldShowPrivatePlacesCategories = false;
    protected bool $shouldShowPrivatePlacesAreas = false;
    protected bool $shouldShowPrivatePlacesContacts = false;
    protected bool $shouldShowPrivatePlacesProducts = false;
    protected bool $shouldShowPrivatePlacesServices = false;
    protected bool $shouldShowPolyline = false;
    protected bool $shouldShowSimplifiedPolyline = false;
    protected bool $shouldShowStaticImage = false;
    protected bool $shouldShowFreightTable = false;
    protected bool $shouldShowLinkToQualP = false;
    protected bool $shouldShowTolls = true;
    protected bool $shouldShowPrivatePlaces = false;
    protected string $format;
    protected string $router = "qualp";

    public function __construct(string $accessToken)
    {
        parent::__construct($accessToken);
    }

    /**
     * @param array $locations
     * @return $this
     * @throws InvalidParamsException
     */
    public function locations(array $locations) : self
    {
        foreach ($locations as $location) {
            if (is_array($location)) {
                if (! array_key_exists('lat', $location) || ! array_key_exists('lng', $location)) {
                    throw InvalidParamsException::invalidLatLngArray();
                }
            }
        }

        $this->locations = $locations;
        $this->polyline = [];

        return $this;
    }

    /**
     * @param array $polyline
     * @return $this
     * @throws InvalidPolylineException
     */
    public function polyline(array $polyline) : self
    {
        if (! array_key_exists('precision', $polyline)) {
            throw InvalidPolylineException::missingPolylinePrecision();
        }

        if (! array_key_exists('string', $polyline)) {
            throw InvalidPolylineException::missingPolylineString();
        }

        if (! is_string($polyline['string'])) {
            throw InvalidPolylineException::invalidPolylineFormat();
        }

        if (! in_array($polyline['precision'], [5, 6])) {
            throw InvalidPolylineException::invalidPolylinePrecision();
        }

        $this->polyline = $polyline;
        $this->locations = [];

        return $this;
    }

    public function vehicle(Vehicles $vehicle) : self
    {
        $this->vehicle = $vehicle->type;

        return $this;
    }

    /**
     * @throws InvalidAxisException
     */
    public function vehicleAxis(int $axis) : self
    {
        if ($axis < 2 || $axis > 10) {
            throw InvalidAxisException::invalidAxisCount();
        }

        $this->vehicleAxis = $axis;

        return $this;
    }

    /**
     * @return $this
     */
    public function usingGoogleRouter() : self
    {
        $this->router = "google";
        return $this;
    }

    /**
     * Make a post request to the API.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post()
    {
        $params = $this->buildParams();

        $response = $this->client->request('POST', '/rotas/v4', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Access-Token' => $this->accessToken
            ],
            'body' => $params,
        ]);

        return $response->getBody();
    }

    /**
     * @throws InvalidParamsException
     */
    public function get()
    {
        if (! empty($this->polyline)) {
            throw InvalidParamsException::cantUsePolylineWithGetMethod();
        }

        $params = json_encode($this->buildParams());

        $response = $this->client->request('GET', '/rotas/v4', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Access-Token' => $this->accessToken
            ],
            'json' => $params,
        ]);

        return $response->getBody();
    }

    private function buildParams() : array
    {
        $params = [
            "config" => [
                "vehicle" => [
                    "axis" => $this->vehicleAxis,
                    "type" => $this->vehicleAxis,
                ],
                "freight_table" => [
                    "category" => $this->freightTableCategory,
                    "freight_load" => $this->freightTableLoad,
                ],
                "route" => [
                    "calculate_return" => $this->shouldCalculateReturn
                ],
                "private_places" => [
                    "max_distance_from_location_to_route" => $this->maxDistanceFromLocationToRoute,
                    "categories" => $this->shouldShowPrivatePlacesCategories,
                    "areas" => $this->shouldShowPrivatePlacesAreas,
                    "contacts" => $this->shouldShowPrivatePlacesContacts,
                    "products" => $this->shouldShowPrivatePlacesProducts,
                    "services" => $this->shouldShowPrivatePlacesServices
                ],
                "router" => $this->router,
            ],
            "show" => [
                "polyline" => $this->shouldShowPolyline,
                "simplified_polyline" => $this->shouldShowSimplifiedPolyline,
                "private_places" => $this->shouldShowPrivatePlaces,
                "static_image" => $this->shouldShowStaticImage,
                "freight_table" => $this->shouldShowFreightTable,
                "link_to_qualp" => $this->shouldShowLinkToQualP,
                "tolls" => $this->shouldShowTolls
            ]
        ];

        if (empty($this->locations)) {
            $params['polyline'] = $this->polyline;
        } else {
            $params['locations'] = $this->locations;
        }

        return $params;
    }
}