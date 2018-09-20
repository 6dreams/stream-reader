<?php
declare(strict_types = 1);

namespace SixDreams;

use PHPUnit\Framework\TestCase;

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
        $found = $this->requestData($collect, $xtract);
        self::assertCount(\count($excepted), $found);
        foreach ($excepted as $id => $chunk) {
            self::assertArrayHasKey($id, $found);
            self::assertEquals($chunk, $found[$id]);
        }
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
     * Data provider for test.
     *
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            ['/xml/sport', '/xml/sport/league/game', [
                '<sport name="football" id="2"><league name="FBL02"><game team1="g1t1" team2="g1t2"><![CDATA[raw]]></game></league></sport>',
                '<sport name="football" id="2"><LEAGUE name="supsuckers"><game team2="test_team_name"><var><![CDATA[test]]></var></game></LEAGUE></sport>',
                '<sport name="snooker" id="1"><league name="SNK01"><game team1="t1" team2="t34"></game></league></sport>'
            ]],
            [null, '/xml/sport/league/game', [
                '<game team1="g1t1" team2="g1t2"><![CDATA[raw]]></game>',
                '<game team2="test_team_name"><var><![CDATA[test]]></var></game>',
                '<game team1="t1" team2="t34"></game>'
            ]],
            ['/xml/sport/league/game', '/xml/sport/league/game', [
                '<game team1="g1t1" team2="g1t2"><![CDATA[raw]]></game>',
                '<game team2="test_team_name"><var><![CDATA[test]]></var></game>',
                '<game team1="t1" team2="t34"></game>'
            ]]
        ];
    }

    /**
     * Request data from parser.
     *
     * @param null|string $collect
     * @param string      $extract
     *
     * @return array
     */
    protected function requestData(?string $collect, string $extract): array
    {
        $handle    = \fopen(__DIR__ . '/sample.xml', 'rb');
        $collected = [];

        try {
            (new StreamReader\XmlStreamReader())
                ->registerCallback($collect, $extract, function (string $data) use (&$collected) {
                    $collected[] = $data;
                })
                ->parse($handle);
        } finally {
            \fclose($handle);
        }

        return $collected;
    }
}
