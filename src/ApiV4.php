<?php

namespace Qualp\Api;

use Qualp\Api\Exceptions\V4\InvalidAxisException;
use Qualp\Api\Exceptions\V4\InvalidParamsException;
use Qualp\Api\Exceptions\V4\InvalidPolylineException;
use Qualp\Api\Support\FreightTable\Category;
use Qualp\Api\Support\FreightTable\Load;
use Qualp\Api\Support\Vehicles;

class ApiV4 extends BaseApi
{
    protected string $url = "http://api.qualp.com.br";
    protected array $locations = [];
    protected array $polyline = [];
    protected string $vehicleType = "";
    protected string $vehicleAxis = "";
    protected string $freightTableCategory = "";
    protected string $freightTableLoad = "";
    protected string $freightTableAxis = "all";
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
    protected string $format = "json";
    protected string $router = "qualp";

    public function __construct(string $accessToken)
    {
        parent::__construct($accessToken);
    }

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

    public function vehicleType(Vehicles $vehicle) : self
    {
        $this->vehicleType = $vehicle->type;

        return $this;
    }

    public function vehicleAxis(string $axis) : self
    {
        if ($axis !== "all") {
            if ((int)$axis < 2 || (int)$axis > 10) {
                throw InvalidAxisException::invalidAxisCount();
            }
        }

        $this->vehicleAxis = $axis;

        return $this;
    }

    public function freightTableCategory(Category $category) : self
    {
        $this->freightTableCategory = $category->category;
        return $this;
    }

    public function freightTableAxis(string $axis) : self
    {
        $this->freightTableAxis = $axis;
        return $this;
    }

    public function freightTableLoad(Load $freightTableLoad) : self
    {
        $this->freightTableLoad = $freightTableLoad->load;

        return $this;
    }

    public function calculateReturn() : self
    {
        $this->shouldCalculateReturn = true;
        return $this;
    }

    public function maxDistanceFromPlacesToRoute(int $distanceInMeters) : self
    {
        $this->maxDistanceFromLocationToRoute = $distanceInMeters;
        return $this;
    }

    public function showPrivatePlacesCategories() : self
    {
        $this->shouldShowPrivatePlacesCategories = true;
        return $this;
    }

    public function showPrivatePlacesAreas() : self
    {
        $this->shouldShowPrivatePlacesAreas = true;
        return $this;
    }

    public function showPrivatePlacesContacts() : self
    {
        $this->shouldShowPrivatePlacesContacts = true;
        return $this;
    }

    public function showPrivatePlacesProducts() : self
    {
        $this->shouldShowPrivatePlacesProducts = true;
        return $this;
    }

    public function showPrivatePlacesServices() : self
    {
        $this->shouldShowPrivatePlacesServices = true;
        return $this;
    }

    public function hideTolls() : self
    {
        $this->shouldShowTolls = false;
        return $this;
    }

    public function showPolyline() : self
    {
        $this->shouldShowPolyline = true;
        return $this;
    }

    public function showSimplifiedPolyline() : self
    {
        $this->shouldShowSimplifiedPolyline = true;
        return $this;
    }

    public function showPrivatePlaces() : self
    {
        $this->shouldShowPrivatePlaces = true;
        return $this;
    }

    public function showStaticImage() : self
    {
        $this->shouldShowStaticImage = true;
        return $this;
    }

    public function showFreightTable() : self
    {
        $this->shouldShowFreightTable = true;
        return $this;
    }

    public function showLinkToQualP() : self
    {
        $this->shouldShowLinkToQualP = true;
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

    public function json() : self
    {
        $this->format = "json";
        return $this;
    }

    public function xml() : self
    {
        $this->format = 'xml';
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
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Access-Token' => $this->accessToken
            ],
            'form_params' => $params,
        ]);

        return $this->buildResponse($response, $this->format);
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

        $response = $this->client->get('/rotas/v4', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Access-Token' => $this->accessToken
            ],
            'query' => ["json" => $params],
        ]);

        return $this->buildResponse($response, $this->format);
    }

    private function buildParams() : array
    {
        $params = [
            "config" => [
                "vehicle" => [
                    "axis" => $this->vehicleAxis,
                    "type" => $this->vehicleType,
                ],
                "freight_table" => [
                    "category" => $this->freightTableCategory,
                    "freight_load" => $this->freightTableLoad,
                    "axis" => $this->freightTableAxis,
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
            ],
            "format" => $this->format
        ];

        if (empty($this->locations)) {
            $params['polyline'] = $this->polyline;
        } else {
            $params['locations'] = $this->locations;
        }

        return $params;
    }
}