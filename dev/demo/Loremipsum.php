<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;


class Loremipsum {

	private static $sprueche = array(

		"Es muss Dienstag gewesen sein. Er hatte die kornblumenblaue Krawatte um.",
		"Dir Federn in den Arsch zu stecken macht dich noch lang‘ nicht zum Huhn.",
		"Du bist nicht dein Job. Du bist nicht das Geld auf deinem Konto. Nicht das Auto, das du fährst! Nicht der Inhalt deiner Brieftasche! Und nicht deine blöde Cargo-Hose.",
		"Alles was du besitzt, besitzt irgendwann dich.",
		"Die Leute, die ich auf jedem Flug kennen lerne sind portionierte Freunde. Zwischen Start und Landung verbringen wir unsere gemeinsame Zeit und das war’s.",
		"Ich sage: sei nie vollständig, ich sage: hör auf perfekt zu sein, ich sage: entwickeln wir uns, lass die Dinge einfach laufen!",
		"Zuerst musst du wissen, nicht fürchten, sondern wissen, dass du einmal sterben wirst.",
		"Zeit, für das, an was du glaubst, aufzustehen.",
			//Quelle: https://mymonk.de/10-lektionen-aus-dem-fight-club-und-die-regeln-des-mymonk-tempels/

		"Mein Name ist Guybrush Threepwood und ich will Pirat werden!",
		"Also du willst Pirat werden, wie? Siehst aber eher wie ein Buchhalter aus.",
		"Unser Grog ist ein Geheimrezept, das einige der folgenden Zutaten enthält: Kerosin, Propylen-Glykol, künstliche Süßstoffe, Schwefelsäure, Rum, Aceton, Rote Farbe, Scumm, Schmierfett, Batteriesäure und/oder Pepperonis. Wie man sich denken kann, ist es eine der ätzendsten Substanzen der Menschheit. Dieses Zeug frißt sich sogar durch diese Krüge, und der Koch verliert ein Vermögen. HAR! HAR! HAR! HAR! HAR! HAR!",
		"Hey, jedem fällt es schwer, seinen Atem frisch zu halten, wenn es außer Ratten nichts zu essen gibt.",
		"Ich hab den Schatz von Mêlée Island gefunden, aber alles, was mir blieb, ist dieses T-Shirt.",
		"Barkeeper: \"Ehrlich, ich mag keine Geschichten, die Leute vom Trinken abhalten sollen.\"",
			//Quelle: http://www.monkeyislandinside.de/sprueche.php

	);

	public static function schlauer_spruch() {
		$spruch = self::$sprueche[rand(0, count(self::$sprueche) - 1)];
		#$spruch="\"ä\"<hr>'ö'\\n\n".$spruch;
		#$spruch=htmlentities($spruch);
		return $spruch;
	}

}