<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Twig_Tests_Profiler_Dumper_TextTest extends Twig_Tests_Profiler_Dumper_AbstractTest
{
    public function testDump()
    {
        $dumper = new Twig_Profiler_Dumper_Text();
        $this->assertStringMatchesFormat(<<<EOF
main %d.%dms/%d%
РІвЂќвЂќ index.twig %d.%dms/%d%
  РІвЂќвЂќ embedded.twig::block(body)
  РІвЂќвЂќ embedded.twig
  РІвЂќвЂљ РІвЂќвЂќ included.twig
  РІвЂќвЂќ index.twig::macro(foo)
  РІвЂќвЂќ embedded.twig
    РІвЂќвЂќ included.twig

EOF
        , $dumper->dump($this->getProfile()));
    }
}
