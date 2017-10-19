<?php
/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2017, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

namespace EllisLab\Tests\ExpressionEngine\Service\Formatter;

use Mockery as m;
use EllisLab\ExpressionEngine\Service\Formatter\Formats\Text;

class TextFormatterTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->lang = m::mock('EE_Lang');
		$this->sess = m::mock('EE_Session');

		$this->lang->shouldReceive('load');
	}

	public function testAccentsToAscii()
	{
		// minimal map
		$config['foreign_chars'] = [
			'223'	=>	"ss", // ß
			'224'	=>  "a",
			'225'	=>  "a",
			'226'	=>	"a",
			'229'	=>	"a",
			'227'	=>	"ae", // ã
			'228'	=>	"ae", // ä
			'230'	=>	"ae", // æ
			'231'	=>	"c",
			'232'	=>	"e",  // è
			'233'	=>	"e",  // é
			'234'	=>	"e",  // ê
			'235'	=>	"e",  // ë
		];

		$text = (string) $this->format('ßaeiouãêëæ ærstlnãêëß', $config)->accentsToAscii();
		$this->assertEquals('ssaeiouaeeeae aerstlnaeeess', $text);
	}

	/**
	 * @dataProvider attributeEscapeProvider
	 */
	public function testAttributeEscape($content, $expected)
	{
		$text = (string) $this->format($content)->attributeEscape();
		$this->assertEquals($expected, $text);
	}

	public function attributeEscapeProvider()
	{
		return [
			['<script>alert("hi");</script>', '&lt;script&gt;alert(&quot;hi&quot;);&lt;/script&gt;'],
			['&"\'<>', '&amp;&quot;&#039;&lt;&gt;'],

			// these should be left alone, would be converted only by htmlentities()
			['©$*@¢£', '©$*@¢£'],
		];
	}

	/**
	 * @dataProvider attributeSafeProvider
	 */
	public function testAttributeSafe($content, $params, $expected)
	{
		$text = (string) $this->format($content)->attributeSafe($params);
		$this->assertEquals($expected, $text);
	}

	public function attributeSafeProvider()
	{
		$sample = 'Some text with "double quotes", <samp>tags</samp>, and some &#8220;typographical quotes&#8221; and &quot;quote entities&quot; that is a bit long';
		return [
			['<script>alert("hi");</script>', [], 'alert(&quot;hi&quot;);'],
			['&"\'<>', [], '&amp;&quot;&#039;'],
			[
				$sample,
				[
					'limit' => 20,
				],
				'Some text with…'
			],
			[
				$sample,
				[
					'limit' => 10,
					'end_char' => 'TEST'
				],
				'SomeTEST'
			],
			[
				$sample,
				[
					'double_encode' => TRUE,
				],
				'Some text with &quot;double quotes&quot;, tags, and some “typographical quotes” and &amp;quot;quote entities&amp;quot; that is a bit long'
			],
			[
				$sample,
				[
					'unicode_punctuation' => FALSE,
				],
				'Some text with &quot;double quotes&quot;, tags, and some &#8220;typographical quotes&#8221; and &quot;quote entities&quot; that is a bit long'
			],
			// these should be left alone, would be converted only by htmlentities()
			['©$*@¢£', [], '©$*@¢£'],
		];
	}

	public function testCensor()
	{
		$this->sess->shouldReceive('cache')->andReturn(FALSE);
		$this->sess->shouldReceive('set_cache');

		$config['censored_words'] = "bleeping\nblarping";

		$text = (string) $this->format('This is a bLeEPing test!', $config)->censor();
		$this->assertEquals('This is a ######## test!', $text);

		$config['censor_replacement'] = 'NOT-IN-MY-HOUSE';

		$text = (string) $this->format('This is a bLeEPing test!', $config)->censor();
		$this->assertEquals('This is a NOT-IN-MY-HOUSE test!', $text);
	}

	/**
	 * @dataProvider convertToEntitiesProvider
	 */
	public function testConvertToEntities($content, $expected)
	{
		$text = (string) $this->format($content)->convertToEntities();
		$this->assertEquals($expected, $text);
	}

	public function convertToEntitiesProvider()
	{
		return [
			['<script>alert("hi");</script>', '&lt;script&gt;alert(&quot;hi&quot;);&lt;/script&gt;'],
			['&"\'<>', '&amp;&quot;&#039;&lt;&gt;'],
			['©$*@¢£', '&copy;$*@&cent;&pound;'],
		];
	}

	public function testEncodeEETags()
	{
		$sample = "
{some_variable}
{exp:query sql='SELECT * FROM exp_members'}{email}{/exp:query}
{embed='foo/bar'}
{path:foo}
{redirect='404'}
{if some_conditional}content{/if}
{layout:variable}
{layout:set name='foo'}bar{/layout:set}";

		$text = (string) $this->format($sample)->encodeEETags();
		$this->assertEquals("
&#123;some_variable&#125;
&#123;exp:query sql='SELECT * FROM exp_members'&#125;&#123;email&#125;&#123;/exp:query&#125;
&#123;embed='foo/bar'&#125;
&#123;path:foo&#125;
&#123;redirect='404'&#125;
&#123;if some_conditional&#125;content&#123;/if&#125;
&#123;layout:variable&#125;
&#123;layout:set name='foo'&#125;bar&#123;/layout:set&#125;", $text);

		$text = (string) $this->format($sample)->encodeEETags(['encode_vars' => FALSE]);

		$this->assertEquals("
{some_variable}
&#123;exp:query sql='SELECT * FROM exp_members'&#125;{email}&#123;/exp:query&#125;
&#123;embed='foo/bar'&#125;
&#123;path:foo&#125;
&#123;redirect='404'&#125;
&#123;if some_conditional}content&#123;/if}
&#123;layout:variable&#125;
&#123;layout:set name='foo'&#125;bar&#123;/layout:set&#125;", $text);
	}

	public function testEncrypt()
	{
		$this->markTestSkipped('This is a gateway to the Encrypt service, cannot unit test in this context');
	}

	/**
	 * @dataProvider formPrepProvider
	 */
	public function testFormPrep($content, $expected)
	{
		if ( ! defined('REQ'))
		{
			define('REQ', 'PAGE');
		}

		require_once __DIR__.'/../../../../../legacy/helpers/form_helper.php';

		$text = (string) $this->format($content)->formPrep();
		$this->assertEquals($expected, $text);
	}

	public function formPrepProvider()
	{
		return [
			['<script>alert("hi");</script>', '&lt;script&gt;alert(&quot;hi&quot;);&lt;/script&gt;'],
			['&"\'<>', '&amp;&quot;&#039;&lt;&gt;'],
			// form_prep tracks prepped strings so this should *not* double-encode things
			['&amp;&quot;&#039;&lt;&gt;', '&amp;&quot;&#039;&lt;&gt;'],
			// and this should be left alone
			['©$*@¢£', '©$*@¢£'],
		];
	}

	public function testJson()
	{
		$sample = '"Hello"	<b>World</b>		&quot;period&quot;.
';
		$text = (string) $this->format($sample)->json();
		$this->assertEquals('"&quot;Hello&quot;\t&lt;b&gt;World&lt;\/b&gt;\t\t&amp;quot;period&amp;quot;.\n"', $text);

		$text = (string) $this->format($sample)->json(['double_encode' => FALSE]);
		$this->assertEquals('"&quot;Hello&quot;\t&lt;b&gt;World&lt;\/b&gt;\t\t&quot;period&quot;.\n"', $text);

		$text = (string) $this->format($sample)->json(['enclose_with_quotes' => FALSE]);
		$this->assertEquals('&quot;Hello&quot;\t&lt;b&gt;World&lt;\/b&gt;\t\t&amp;quot;period&amp;quot;.\n', $text);

		$text = (string) $this->format($sample)->json(['options' => 'JSON_HEX_AMP|JSON_HEX_TAG']);
		$this->assertEquals('"\u0026quot;Hello\u0026quot;\t\u0026lt;b\u0026gt;World\u0026lt;\/b\u0026gt;\t\t\u0026amp;quot;period\u0026amp;quot;.\n"', $text);
	}

	public function tearDown()
	{
		$this->factory = NULL;
	}

	public function format($content, $config = [])
	{
		return new Text($content, $this->lang, $this->sess, $config, 0b00000001);
	}
}

// EOF
