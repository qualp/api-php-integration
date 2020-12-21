<?php

namespace Qualp\Api;

use Qualp\Api\Exceptions\V3\InvalidParamsException;
use Qualp\Api\Exceptions\V3\InvalidPolylineException;
use Qualp\Api\Support\FreightTable\Category;

class ApiV3 extends BaseApi
{
    protected string $origin = "";
    protected string $destinations = "";
    protected string $category = "";
    protected int $axis = 0;
    protected bool $shouldCalculateReturn = false;
    protected bool $shouldCalculateFreightTable = false;
    protected string $freightTableCategory = "";
    protected bool $showStaticImage = false;
    protected string $format = "json";
    protected int $polylinePrecision = 6;
    protected string $polyline = "";


    public function __construct(string $accessToken)
    {
        parent::__construct($accessToken);
    }

    public function json() : self
    {
        $this->format = "json";
        return $this;
    }

    public function xml() : self
    {
        $this->format = "xml";
        return $this;
    }

    /**
     * @param string $origin
     * @return $this
     */
    public function origin(string $origin) : self
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @param array $destinations
     * @return $this
     */
    public function destinations(array $destinations) : self
    {
        $this->destinations = implode('|', $destinations);
        return $this;
    }

    /**
     * @param string $category
     * @return $this
     * @throws InvalidParamsException
     */
    public function vehicleCategory(string $category) : self
    {
        if (! in_array(strtolower($category), ['caminhao', 'carro', 'onibus', 'moto'])) {
            throw InvalidParamsException::invalidVehicleCategory();
        }
        $this->category = $category;
        return $this;
    }

    /**
     * @param int $axis
     * @return $this
     * @throws InvalidParamsException
     */
    public function axis(int $axis) : self
    {
        if ($axis < 1 || $axis > 15) {
            throw InvalidParamsException::invalidAxisCount();
        }

        $this->axis = $axis;
        return $this;
    }

    public function calculateReturn() : self
    {
        $this->shouldCalculateReturn = true;
        return $this;
    }

    public function showFreightTable() : self
    {
        $this->shouldCalculateFreightTable = true;
        return $this;
    }

    public function showStaticImage() : self
    {
        $this->showStaticImage = true;
        return $this;
    }

    public function freightTableCategory(Category $freightTableCategory) : self
    {
        $this->freightTableCategory = $freightTableCategory->category;
        return $this;
    }

    /**
     * @param string $polyline
     * @return $this
     */
    public function polyline(string $polyline) : self
    {
        $this->origin = "";
        $this->destinations = "";
        $this->polyline = $polyline;
        return $this;
    }

    /**
     * @param int $precision
     * @return $this
     * @throws InvalidPolylineException
     */
    public function polylinePrecision(int $precision) : self
    {
        if (! in_array($precision, [5, 6])) {
            throw InvalidPolylineException::invalidPolylinePrecision();
        }
        $this->polylinePrecision = $precision;

        return $this;
    }


    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get()
    {
        $response = $this->client->get('/rotas/v3', [
            'query' => $this->buildParams()
        ]);

        return $this->buildResponse($response, $this->format);
    }

    public function post()
    {
        $response = $this->client->post('/rotas/v3', [
            'headers' => [
                'Content-Type' => 'application/x-www-urlencoded',
                'Content-Length' => 0,
                'Accept' => '*/*'
            ],
            'form_params' => $this->buildParams()
        ]);

        return $this->buildResponse($response, $this->format);
    }

    private function buildParams() : array
    {
        $params = [
            "access-token" => $this->accessToken,
            "categoria" => $this->category,
            "eixos" => $this->axis,
            "calcular-volta" => $this->shouldCalculateReturn ? "sim" : "nao",
            "tabela-frete" => $this->shouldCalculateFreightTable ? "sim" : "nao",
            "categoria-tabela-frete" => $this->freightTableCategory,
            "rota-imagem" => $this->showStaticImage ? "sim" : "nao",
            "format" => $this->format
        ];

        if (!empty($this->polyline)) {
            $params['polilinha'] = $this->polyline;
            $params['precisao-polilinha'] = $this->polylinePrecision;
        } else {
            $params["origem"] = $this->origin;
            $params["destinos"] = $this->destinations;
        }

        return $params;
    }
}