<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Pers�nuverndarstefna Istorrent");
begin_main_frame();
begin_frame('Pers�nuverndarstefna Istorrent');
echo '
<ul>
<li>Allar pers�nuuppl�singar sem notandi leggur fram eru eing�ngu a�gengilegar stj�rnendum og �eim sem fara inn � a�ganginn sem uppl�singarnar eru tengdar vi�.</li>
<li>Pers�nuuppl�singar teljast ��r uppl�singar sem eru ekki a�gengilegar ��rum notendum me� leyf�um a�ger�um.</li>
<li>Uppl�singar teljast ekki pers�nuuppl�singar ef notandi lag�i ��r fram, vitandi a� ��r yr�u a�gengilegar ��rum notendum e�a hefur gefi� leyfi fyrir dreifingu �eirra.</li>
<li>Notandanafn, skr�ningardagur, seinasta fletting, samtals deilt gagnamagn, samtals s�tt gagnamagn, hlutfall, sta�a notanda, hvort notandi hafi styrkt Istorrent, fj�lda athugasemda og fj�ldi spjallp�sta skulu teljast sem opinber g�gn.</li>
<li>Lykilor� eru ekki a�gengileg stj�rnendum � �dulk��u�u formi.</li>
<li>Einkaskilabo� milli notenda skulu teljast sem pers�nuuppl�singar en stj�rnendum er �heimilt a� lesa skilabo� annarra notenda nema um s� a� r��a ranns�kn � alvarlegum brotum � l�gum og reglum. Efni skilabo�anna skal haldast sem tr�na�arm�l innan Istorrent en Istorrent er �� heimilt a� framv�sa g�gnum til l�greglu ef um er a� r��a alvarleg brot � landsl�gum.</li>
<li>Skilabo� sem berast stj�rnanda vegna st��u hans skal me�h�ndla sem pers�nuuppl�singar samkv�mt stefnu �essari.</li>
<li>Engar pers�nuppl�singar eru l�tnar af hendi til a�ila sem ekki hafa til �ess heimild samkv�mt pers�nuverndarstefnu Istorrent nema vegna d�ms�rskur�s opinbers d�msvalds � �slandi.</li>
<li>Uppl�singar sem notandi leggur inn til a� sta�festa styrki skulu teljast sem pers�nuuppl�singar.</li>
<li>Komi upp s� sta�a a� notandi reyni a� n�ta s�r �agnarskyldu Istorrent � pers�nuverndarm�lum til a� grafa undir trausti starfsseminnar, t.d. me� �v� a� vitna rangt � einkasamskipti vi� fulltr�a Istorrent, �� er stj�rninni heimilt a� opinbera vi�komandi pers�nutengd g�gn sem Istorrent hefur tengd vi� a�ganginn � tilraun til a� laga �a� skemmda �lit sem notandinn reyndi a� valda.</li>
<li>�byrg�ara�ilar �essarar stefnu eru opinberir stj�rnendur Istorrent</li>
</ul>
';
end_frame();
end_main_frame();
stdfoot();
?>
