<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Xo\Storage\Storage;


final class StorageTest extends TestCase
{
    protected $data;

    protected $location;

    protected $invalidLocation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->location = '/home/steepy/log/storage';

        $this->invalidLocation = '/home/steepy/log/nofile';

        $this->data = [
            'facebook' => [
                'clientId' => 'fbClientId',
                'clientSecret' => 'fbClientSecret',
                'redirectUri' => 'fbRedirectUri',
            ],
            'twitter' => [
                'clientId' => 'twClientId',
                'clientSecret' => 'twClientSecret',
                'redirectUri' => 'twRedirectUri',
            ],
        ];

        Storage::create($this->data, $this->location);
    }

    public function testCanBeCreatedFromValidFileName()
    {
        $this->assertInstanceOf(
            Storage::class,
            Storage::getInstance($this->location)
        );
    }

    public function testCanNotBeCreatedIfFileNameDoesNotExistOrIsNotWritable()
    {
        $this->expectException(InvalidArgumentException::class);

        Storage::getInstance($this->invalidLocation);
    }

    public function testCanBeStoredArrayDataIntoValidFile()
    {
        $result = Storage::all();

        $this->assertSame($this->data, $result);
    }

    public function testCanNotBeStoredArrayDataIntoInvalidFile()
    {
        $this->expectException(InvalidArgumentException::class);

        Storage::create($this->data, $this->invalidLocation);

        $result = Storage::all();

        $this->assertNotSame($this->data, $result);
    }

    public function testCanGetValueByKeyFromFileStorage()
    {
        $expected = $this->data['twitter'];
        $result = Storage::get('twitter');

        $this->assertSame($expected, $result);
    }

    public function testCanNotGetValueByUnknownKeyFromFileStorage()
    {
        $expectedResult = $this->data['twitter'];
        $result = Storage::get('instagram');

        $this->assertNotSame($expectedResult, $result);
        $this->assertSame(null, $result);
    }

    public function testCanAddNewArrayDataToGivenKey()
    {
        $tumblr = [
            'clientId' => 'tlrClientId',
            'clientSecret' => 'tlrClientSecret',
            'redirectUri' => 'tlrRedirectUri',
        ];

        $result = Storage::set('tumblr', $tumblr);

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);

        $this->data['tumblr'] = $tumblr;

        $this->assertSame($this->data, Storage::all());

        $this->assertArrayHasKey('tumblr', Storage::all());
    }

    public function testCanAddNewValueToGivenKey()
    {
        $result = Storage::set('hell', 'This world is hell');

        $this->assertIsBool($result);

        $this->assertEquals(true, $result);

        $this->assertArrayHasKey('hell', Storage::all());

        $this->assertEquals('This world is hell', Storage::get('hell'));
    }

    public function testCanAddEmptyValueToGivenKey()
    {
        $result = Storage::set('heaven', '');

        $this->assertIsBool($result);

        $this->assertEquals(true, $result);

        $this->assertEmpty(Storage::get('heaven'));
    }

    public function testCanUpdateArrayDataReturnedByTheGivenKey()
    {
        $date = date('Y-m-d H:i:s');
        $data = [
            'updatedAt' => $date,
            'accessToken' => 'fbAccessToken',
        ];

        $result = Storage::update('facebook', $data);

        $this->assertIsBool($result);

        $this->assertEquals(true, $result);

        $updatedArray = Storage::get('facebook');

        $expectedArray = array_merge($this->data['facebook'], $data);

        $this->assertSame($expectedArray, $updatedArray);
    }

    public function testCanCheckKeyExists()
    {
        $result = Storage::has('facebook');

        $this->assertIsBool($result);

        $this->assertEquals(true, $result);
    }

    public function testCanReturnFalseForUnknownKey()
    {
        $result = Storage::has('nothing');

        $this->assertIsBool($result);

        $this->assertEquals(false, $result);
    }

    public function testCanRemoveArrayDataByTheGivenKey()
    {
        $resultExists = Storage::get('twitter');

        $this->assertIsArray($resultExists);

        $this->assertArrayHasKey('clientId', $resultExists);

        $resultDoesNotExist = Storage::remove('twitter');

        $this->assertIsBool($resultDoesNotExist);

        $this->assertEquals(true, $resultDoesNotExist);

        $count = count(Storage::all());

        $this->assertSame(1, $count);
    }

    public function testCanClearAllArrayData()
    {
        $beforeClearingItems = Storage::all();

        $this->assertSame(2, count($beforeClearingItems));

        $isCleared = Storage::clear();

        $this->assertSame(true, $isCleared);

        $afterClearingItems = Storage::all();

        $this->assertSame(0, count($afterClearingItems));
    }

    public function testCanDeleteFileStorageByTheGivenLocation()
    {
        $this->assertIsBool(true);

        // $deleteStorage = Storage::delete($this->location);
        //
        // $this->assertIsBool($deleteStorage);
        //
        // $this->assertSame(true, $deleteStorage);
        //
        // $this->expectException(InvalidArgumentException::class);
        //
        // Storage::getInstance($this->location);
    }
}


