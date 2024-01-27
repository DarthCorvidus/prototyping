<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class SHA1Test extends TestCase {
	function testRotateLeft() {
		/**
		 * We use literal 32bit strings here to make the test more transparent.
		 */
		$this->assertEquals("00000000000000000000000000000001", sprintf("%032b", SHA1::rotateLeft(bindec("10000000000000000000000000000000"), 1)));
		$this->assertEquals("00000000000000000000000000000010", sprintf("%032b", SHA1::rotateLeft(bindec("00000000000000000000000000000001"), 1)));
		$this->assertEquals("11111111111111111111111111111111", sprintf("%032b", SHA1::rotateLeft(bindec("11111111111111111111111111111111"), 1)));
		$this->assertEquals("00000000000010001001100010000101", sprintf("%032b", SHA1::rotateLeft(bindec("01000000000000100010011000100001"), 2)));
		$this->assertEquals("01000000000000100010011000100001", sprintf("%032b", SHA1::rotateLeft(bindec("01000000000000100010011000100001"), 32)));
		$this->assertEquals("00100110001000010100000000000010", sprintf("%032b", SHA1::rotateLeft(bindec("01000000000000100010011000100001"), 16)));

	}
	
	function testExpandChunk() {
		$chunk = "The big brown fox jumped over the sleeping and very lazy pugdog.";
		$expected[0] = 1416127776;
		$expected[1] = 1651074848;
		$expected[2] = 1651666807;
		$expected[3] = 1847617135;
		$expected[4] = 2015390325;
		$expected[5] = 1836082532;
		$expected[6] = 544175717;
		$expected[7] = 1914729576;
		$expected[8] = 1696625516;
		$expected[9] = 1701146729;
		$expected[10] = 1852252257;
		$expected[11] = 1852055670;
		$expected[12] = 1702000928;
		$expected[13] = 1818327673;
		$expected[14] = 544240999;
		$expected[15] = 1685022510;
		$expected[16] = 2125858436;
		$expected[17] = 2461534338;
		$expected[18] = 548701338;
		$expected[19] = 654593010;
		$expected[20] = 1594547045;
		$expected[21] = 2802573278;
		$expected[22] = 2222356280;
		$expected[23] = 1480683668;
		$expected[24] = 2784302255;
		$expected[25] = 1000234826;
		$expected[26] = 3888208542;
		$expected[27] = 31984293;
		$expected[28] = 1127026896;
		$expected[29] = 2441687086;
		$expected[30] = 3064995069;
		$expected[31] = 3681951697;
		$expected[32] = 3573370174;
		$expected[33] = 1898185102;
		$expected[34] = 2249340256;
		$expected[35] = 2855642990;
		$expected[36] = 3538647559;
		$expected[37] = 3517142025;
		$expected[38] = 2047717384;
		$expected[39] = 3565458448;
		$expected[40] = 2396302860;
		$expected[41] = 1656735954;
		$expected[42] = 3975534205;
		$expected[43] = 1761634259;
		$expected[44] = 2341699056;
		$expected[45] = 4015217430;
		$expected[46] = 3800108080;
		$expected[47] = 3935039359;
		$expected[48] = 1724979848;
		$expected[49] = 3059663876;
		$expected[50] = 2769218762;
		$expected[51] = 3901693048;
		$expected[52] = 710338551;
		$expected[53] = 2666852234;
		$expected[54] = 4256046233;
		$expected[55] = 3989612692;
		$expected[56] = 873011943;
		$expected[57] = 2157899576;
		$expected[58] = 1598676902;
		$expected[59] = 3047847092;
		$expected[60] = 2276782623;
		$expected[61] = 2295007371;
		$expected[62] = 2561211691;
		$expected[63] = 1834673120;
		$expected[64] = 4265330780;
		$expected[65] = 2379191518;
		$expected[66] = 2056679159;
		$expected[67] = 2077531700;
		$expected[68] = 3137813343;
		$expected[69] = 46809797;
		$expected[70] = 1438042818;
		$expected[71] = 1981759015;
		$expected[72] = 794992561;
		$expected[73] = 3667626785;
		$expected[74] = 2833840851;
		$expected[75] = 3551460212;
		$expected[76] = 4244733588;
		$expected[77] = 2674304762;
		$expected[78] = 3239446915;
		$expected[79] = 3608571674;
		$ints = SHA1::expand($chunk);
		$this->assertEquals($expected, $ints);
		#ee3c7ee620acede545810ec594737b55bc972736507184618fb7cd725e0516c2458b7eb71dd6cac93621c079f3764fde058ae2fb121c747f51cdd133a48eecd9
	}
}
