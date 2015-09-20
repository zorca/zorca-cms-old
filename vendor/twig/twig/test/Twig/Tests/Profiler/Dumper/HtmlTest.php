<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Twig_Tests_Profiler_Dumper_HtmlTest extends Twig_Tests_Profiler_Dumper_AbstractTest
{
    public function testDump()
    {
        $dumper = new Twig_Profiler_Dumper_Html();
        $this->assertStringMatchesFormat(<<<EOF
<pre>main <span style="color: #d44">%d.%dms/%d%</span>
РІвЂќвЂќ <span style="background-color: #ffd">index.twig</span> <span style="color: #d44">%d.%dms/%d%</span>
  РІвЂќвЂќ embedded.twig::block(<span style="background-color: #dfd">body</span>)
  РІвЂќвЂќ <span style="background-color: #ffd">embedded.twig</span>
  РІвЂќвЂљ РІвЂќвЂќ <span style="background-color: #ffd">included.twig</span>
  РІвЂќвЂќ index.twig::macro(<span style="background-color: #ddf">foo</span>)
  РІвЂќвЂќ <span style="background-color: #ffd">embedded.twig</span>
    РІвЂќвЂќ <span style="background-color: #ffd">included.twig</span>
</pre>
EOF
        , $dumper->dump($this->getProfile()));
    }
}
