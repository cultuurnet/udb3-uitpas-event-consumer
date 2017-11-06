<?php

namespace CultuurNet\UDB3\UiTPAS\EventConsumer\Label;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class HttpUiTPASLabelsRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var HttpUiTPASLabelsRepository
     */
    private $repository;

    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);
        $this->endpoint = 'http://uitpas.dev/labels';

        $this->repository = new HttpUiTPASLabelsRepository($this->httpClient, $this->endpoint);
    }

    /**
     * @test
     */
    public function it_should_return_an_array_of_uitpas_labels()
    {
        $expected = [
            'Paspartoe',
            'UiTPAS',
            'UiTPAS Gent',
            'UiTPAS Oostende',
            'UiTPAS regio Aalst',
            'UiTPAS Dender',
            'UiTPAS Zuidwest',
            'UiTPAS Mechelen',
            'UiTPAS Kempen',
            'UiTPAS Maasmechelen',
        ];

        $json = '
        {
          "PASPARTOE": "Paspartoe",
          "UITPAS": "UiTPAS",
          "UITPAS_GENT": "UiTPAS Gent",
          "UITPAS_OOSTENDE": "UiTPAS Oostende",
          "UITPAS_REGIO_AALST": "UiTPAS regio Aalst",
          "UITPAS_DENDER": "UiTPAS Dender",
          "UITPAS_ZUIDWEST": "UiTPAS Zuidwest",
          "UITPAS_MECHELEN": "UiTPAS Mechelen",
          "UITPAS_KEMPEN": "UiTPAS Kempen",
          "UITPAS_MAASMECHELEN": "UiTPAS Maasmechelen"
        }';

        $request = $this->createMock(Request::class);

        $response = $this->createMock(Response::class);

        $this->httpClient->expects($this->once())
            ->method('get')
            ->with($this->endpoint)
            ->willReturn($request);

        $request->expects($this->once())
            ->method('send')
            ->willReturn($response);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($json);

        $actual = $this->repository->loadAll();

        $this->assertEquals($expected, $actual);
    }
}
