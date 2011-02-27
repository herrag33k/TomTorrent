<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Persónuverndarstefna Istorrent");
begin_main_frame();
begin_frame('Persónuverndarstefna Istorrent');
echo '
<ul>
<li>Allar persónuupplýsingar sem notandi leggur fram eru eingöngu aðgengilegar stjórnendum og þeim sem fara inn á aðganginn sem upplýsingarnar eru tengdar við.</li>
<li>Persónuupplýsingar teljast þær upplýsingar sem eru ekki aðgengilegar öðrum notendum með leyfðum aðgerðum.</li>
<li>Upplýsingar teljast ekki persónuupplýsingar ef notandi lagði þær fram, vitandi að þær yrðu aðgengilegar öðrum notendum eða hefur gefið leyfi fyrir dreifingu þeirra.</li>
<li>Notandanafn, skráningardagur, seinasta fletting, samtals deilt gagnamagn, samtals sótt gagnamagn, hlutfall, staða notanda, hvort notandi hafi styrkt Istorrent, fjölda athugasemda og fjöldi spjallpósta skulu teljast sem opinber gögn.</li>
<li>Lykilorð eru ekki aðgengileg stjórnendum í ódulkóðuðu formi.</li>
<li>Einkaskilaboð milli notenda skulu teljast sem persónuupplýsingar en stjórnendum er óheimilt að lesa skilaboð annarra notenda nema um sé að ræða rannsókn á alvarlegum brotum á lögum og reglum. Efni skilaboðanna skal haldast sem trúnaðarmál innan Istorrent en Istorrent er þó heimilt að framvísa gögnum til lögreglu ef um er að ræða alvarleg brot á landslögum.</li>
<li>Skilaboð sem berast stjórnanda vegna stöðu hans skal meðhöndla sem persónuupplýsingar samkvæmt stefnu þessari.</li>
<li>Engar persónupplýsingar eru látnar af hendi til aðila sem ekki hafa til þess heimild samkvæmt persónuverndarstefnu Istorrent nema vegna dómsúrskurðs opinbers dómsvalds á Íslandi.</li>
<li>Upplýsingar sem notandi leggur inn til að staðfesta styrki skulu teljast sem persónuupplýsingar.</li>
<li>Komi upp sú staða að notandi reyni að nýta sér þagnarskyldu Istorrent í persónuverndarmálum til að grafa undir trausti starfsseminnar, t.d. með því að vitna rangt í einkasamskipti við fulltrúa Istorrent, þá er stjórninni heimilt að opinbera viðkomandi persónutengd gögn sem Istorrent hefur tengd við aðganginn í tilraun til að laga það skemmda álit sem notandinn reyndi að valda.</li>
<li>Ábyrgðaraðilar þessarar stefnu eru opinberir stjórnendur Istorrent</li>
</ul>
';
end_frame();
end_main_frame();
stdfoot();
?>
