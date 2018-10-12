<?php
declare(strict_types = 1);

namespace SixDreams;

use PHPUnit\Framework\TestCase;
use SixDreams\StreamReader\XmlStreamReader;

/**
 * Class XmlStreamReaderTest
 */
class XmlStreamReaderTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param null|string $collect
     * @param string      $xtract
     * @param array       $excepted
     */
    public function testBase(?string $collect, string $xtract, array $excepted): void
    {
        $this->assertStringArray($this->requestData($collect, $xtract), $excepted);
    }

    /**
     * @dataProvider lowerCaseProvider
     *
     * @param null|string $collect
     * @param string      $xtract
     * @param array       $excepted
     */
    public function testLowerCase(?string $collect, string $xtract, array $excepted): void
    {
        $this->assertStringArray($this->requestData($collect, $xtract, function (XmlStreamReader $reader) {
            $reader->setLowerCaseNames(true);
        }), $excepted);
    }

    /**
     * Tests invalid path.
     */
    public function testInvalidPath(): void
    {
        $this->expectExceptionMessage('Path must extractPath must contain collectPath!');
        $this->requestData('/a/b/c', '/h/c/d/e');
    }

    /**
     * Test for invalid file.
     */
    public function testInvalidXml(): void
    {
        $this->expectExceptionMessageRegExp('/XML Parse Error: \d+ at line \d+/');
        $this->requestData('/test', '/test/dno', null, 'invalid.xml');
    }

    /**
     * Data provider for @see testLowerCase.
     *
     * @return array
     */
    public function lowerCaseProvider(): array
    {
        return [
            ['/xml/sport', '/xml/sport/league/game', [
                '<sport name="football+game" id="2"><league name="FBL02"><game team1="g1t1" team2="g1t2"><![CDATA[RaW]]></game></league></sport>',
                '<sport name="football+game" id="2"><league name="supsuckers"><game team2="test_team_name"><var><![CDATA[TeSt]]></var></game></league></sport>',
                '<sport name="snooker" id="1"><league name="SNK01"><game team1="t1" team2="t34"></game></league></sport>'
            ]]
        ];
    }

    /**
     * Data provider for @see testBase.
     *
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            ['/xml/sport', '/xml/sport/league/game', [
                '<sport name="football+game" id="2"><league name="FBL02"><game team1="g1t1" team2="g1t2"><![CDATA[RaW]]></game></league></sport>',
                '<sport name="football+game" id="2"><LEAGUE name="supsuckers"><game Team2="test_team_name"><var><![CDATA[TeSt]]></var></game></LEAGUE></sport>',
                '<sport name="snooker" id="1"><league name="SNK01"><game team1="t1" team2="t34"></game></league></sport>'
            ]],
            [null, '/xml/sport/league/game', [
                '<game team1="g1t1" team2="g1t2"><![CDATA[RaW]]></game>',
                '<game Team2="test_team_name"><var><![CDATA[TeSt]]></var></game>',
                '<game team1="t1" team2="t34"></game>'
            ]],
            ['/xml/sport/league/game', '/xml/sport/league/game', [
                '<game team1="g1t1" team2="g1t2"><![CDATA[RaW]]></game>',
                '<game Team2="test_team_name"><var><![CDATA[TeSt]]></var></game>',
                '<game team1="t1" team2="t34"></game>'
            ]]
        ];
    }

    /**
     * Request data from parser.
     *
     * @param null|string   $collect
     * @param string        $extract
     * @param callable|null $cb
     * @param string        $file
     *
     * @return array
     */
    protected function requestData(?string $collect, string $extract, ?callable $cb = null, $file = 'sample.xml'): array
    {
        $handle    = \fopen(__DIR__ . DIRECTORY_SEPARATOR . $file, 'rb');
        $collected = [];

        try {
            $object = (new XmlStreamReader())
                ->registerCallback($collect, $extract, function (string $data) use (&$collected) {
                    $collected[] = $data;
                });

            if ($cb) {
                $cb($object);
            }

            $object->parse($handle);
        } finally {
            \fclose($handle);
        }

        return $collected;
    }

    /**
     * Make asserts on array.
     *
     * @param array $actual
     * @param array $excepted
     */
    protected function assertStringArray(array $actual, array $excepted): void
    {
        self::assertCount(\count($excepted), $actual);
        foreach ($excepted as $id => $chunk) {
            self::assertArrayHasKey($id, $actual);
            self::assertEquals($chunk, $actual[$id]);
        }
    }
}
