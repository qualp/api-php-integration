<?php

namespace Qualp\Api;

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

    public function origin(string $origin) : self
    {
        $this->origin = $origin;
        return $this;
    }

    public function destinations(array $destinations) : self
    {
        $this->destinations = implode('|', $destinations);
        return $this;
    }

    public function category(string $category) : self
    {
        $this->category = $category;
        return $this;
    }

    public function axis(int $axis) : self
    {
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

    public function freightTableCategory(string $freightTableCategory) : self
    {
        $this->freightTableCategory = $freightTableCategory;
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
            "origem" => $this->origin,
            "destinos" => $this->destinations,
            "categoria" => $this->category,
            "eixos" => $this->axis,
            "calcular-volta" => $this->shouldCalculateReturn,
            "tabela-frete" => $this->shouldCalculateFreightTable ? "sim" : "nao",
            "categoria-tabela-frete" => $this->freightTableCategory,
            "rota-imagem" => $this->showStaticImage ? "sim" : "nao",
            "format" => $this->format
        ];

        return $params;
    }
}