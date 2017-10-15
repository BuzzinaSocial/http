<?php

namespace BuzzinaSocial\Http\Tests;

/**
 * Class ClientTest.
 *
 * @package BuzzinaSocial\Http\Tests
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $client_reflection;

    public function setUp()
    {
        $this->client = $this->getMockForAbstractClass(\BuzzinaSocial\Http\Client::class);
        $this->client_reflection = new \ReflectionClass(\BuzzinaSocial\Http\Client::class);
    }

    public function testConstructorCallsInternalMethods()
    {
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $this->client->getHttpClient());
    }

    public function testIfTraitJsonRenderAsInstantiated()
    {
        $this->assertTrue(trait_exists(\BuzzinaSocial\Traits\JsonRender::class));
        $this->assertTrue(method_exists(\BuzzinaSocial\Http\Client::class, 'jsonEncode'));
        $this->assertTrue(method_exists(\BuzzinaSocial\Http\Client::class, 'jsonDecode'));
    }

    public function testSituationClass()
    {
        $this->assertTrue($this->client_reflection->isAbstract());
        $this->assertTrue($this->client_reflection->inNamespace());
        $this->assertEquals($this->client_reflection->getName(), \BuzzinaSocial\Http\Client::class);
        $this->assertEquals($this->client_reflection->getNamespaceName(), 'BuzzinaSocial\Http');
        $this->assertFalse($this->client_reflection->getParentClass());
        $this->assertEquals('Client', $this->client_reflection->getShortName());
    }

    public function testConstantGET()
    {
        $this->assertTrue($this->client_reflection->hasConstant('GET'));
        $this->assertEquals('GET', $this->client_reflection->getConstant('GET'));
        $this->assertInternalType('string', $this->client_reflection->getConstant('GET'));
    }

    public function testConstantPOST()
    {
        $this->assertTrue($this->client_reflection->hasConstant('POST'));
        $this->assertEquals('POST', $this->client_reflection->getConstant('POST'));
        $this->assertInternalType('string', $this->client_reflection->getConstant('POST'));
    }

    public function testConstantPUT()
    {
        $this->assertTrue($this->client_reflection->hasConstant('PUT'));
        $this->assertEquals('PUT', $this->client_reflection->getConstant('PUT'));
        $this->assertInternalType('string', $this->client_reflection->getConstant('PUT'));
    }

    public function testConstantDELETE()
    {
        $this->assertTrue($this->client_reflection->hasConstant('DELETE'));
        $this->assertEquals('DELETE', $this->client_reflection->getConstant('DELETE'));
        $this->assertInternalType('string', $this->client_reflection->getConstant('DELETE'));
    }

    public function testIfHasPropertyHttpClientByDefault()
    {
        $this->assertTrue($this->client_reflection->hasProperty('http_client'));
        $property = $this->client_reflection->getProperty('http_client');
        $this->assertTrue($property->isDefault());
        $this->assertTrue($property->isProtected());
    }

    public function testIfHasPropertySettingsByDefault()
    {
        $this->assertTrue($this->client_reflection->hasProperty('settings'));
        $property = $this->client_reflection->getProperty('settings');
        $this->assertTrue($property->isDefault());
        $this->assertTrue($property->isProtected());
    }

    public function testGetAndSetHeader()
    {
        $this->assertEmpty($this->client->getHeaders());

        $header_1 = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        $header_2 = [
            'x-Api-Key' => 'XXT',
            'X-User-Email' => 'jean@comunicaweb.com.br',
            'x-accountmanager-key' => 'FooBar'
        ];

        $this->assertNull($this->client->setHeaders($header_1));
        $this->assertEquals($this->client->getHeaders(), $header_1);
        $this->assertEquals(2, count($this->client->getHeaders()));


        $this->assertNull($this->client->setHeaders($header_2));
        $this->assertEquals($this->client->getHeaders(), array_merge($header_1, $header_2));
        $this->assertEquals(5, count($this->client->getHeaders()));

        $this->assertInternalType('array', $this->client->getHeaders());

        $this->expectException(\TypeError::class);
        $this->client->setHeaders('string');
        $this->client->setHeaders(01);
    }

    /**
     * @dataProvider baseURIValidedProvider
     */
    public function testGetAndSetBaseURI($link)
    {
        $this->assertEmpty($this->client->getBaseURI());
        $this->assertNull($this->client->setBaseURI($link));
        $this->assertEquals($link, $this->client->getBaseURI());
    }

    public function baseURIValidedProvider()
    {
        return [
            ['https://api-marketplace.bonmarketplace.com.br'],
            ['https://api-sandbox.bonmarketplace.com.br'],
            ['https://bling.com.br/Api/v2/'],
            ['https://api.cnova.com/api/v2/'],
            ['https://sandbox.cnova.com/api/v2/'],
            ['https://lojista.ehub.com.br/oauth-api/authorize'],
            ['https://api.cnova.com/oauth/access_token'],
            ['https://api.mercadolibre.com'],
            ['https://auth.mercadolibre.com.ar'],
            ['https://auth.mercadolivre.com.br'],
            ['https://auth.mercadolibre.com.co'],
            ['https://auth.mercadolibre.com.cr'],
            ['https://auth.mercadolibre.com.ec'],
            ['https://auth.mercadolibre.cl'],
            ['https://auth.mercadolibre.com.mx'],
            ['https://auth.mercadolibre.com.uy'],
            ['https://auth.mercadolibre.com.ve'],
            ['https://auth.mercadolibre.com.pa'],
            ['https://auth.mercadolibre.com.pe'],
            ['https://auth.mercadolibre.com.pt'],
            ['https://auth.mercadolibre.com.do'],
            ['http://open.api.sandbox.ebay.com/'],
            ['https://api-mp.walmart.com.br/ws/seller/%u/'],
            ['https://adapter.waldev.com.br/ws/seller/%u/'],
            ['https://pandora.vtexcommercestable.com.br/api/'],
            ['https://api.tiny.com.br/api2/'],
            ['https://api.mercadoshops.com/v1/'],
        ];
    }

    public function testInvalidBaseURI()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Base uri Inválido.');

        $this->client->setBaseURI('mercadolivre.com.br');
    }

    public function testSetHeaderKeyInSettings()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível setar o header nem o base uri através dessa função.');

        $this->client->setSettings('headers', []);
    }

    public function testSetBaseURIKeyInSettings()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível setar o header nem o base uri através dessa função.');

        $this->client->setSettings('base_uri', 'uri');
    }

    public function testGetHeaderKeyInSettings()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível resgatar o header nem o base uri através dessa função.');

        $this->client->getSettings('headers');
    }

    public function testGetBaseURIKeyInSettings()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível resgatar o header nem o base uri através dessa função.');

        $this->client->getSettings('base_uri');
    }

    public function testGetNonexistentKeyInSettings()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Herder não exite.');

        $this->client->getSettings('NonexistentKey');
    }

    public function testGetSetInSettings()
    {
        $this->assertEquals(['headers' => [],'base_uri' => ''], $this->client->getSettings());
        $this->assertNull($this->client->setSettings('auth', ['Foo', 'Bar']));
        $this->assertEquals(['Foo', 'Bar'], $this->client->getSettings('auth'));
    }

    public function testRequestGet()
    {
        $request = $this->client->get('https://httpbin.org/get');

        $this->assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $request);
    }

    public function testRequestPost()
    {
        $request = $this->client->post('https://httpbin.org/post', ['foo' => 'bar']);

        $this->assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $request);
    }

    public function testRequestPut()
    {
        $request = $this->client->put('https://httpbin.org/put', json_encode(['foo' => 'bar']));

        $this->assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $request);
    }

    public function testRequestDelete()
    {
        $request = $this->client->delete('https://httpbin.org/delete');

        $this->assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $request);
    }
}
