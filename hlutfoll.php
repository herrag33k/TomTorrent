<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Af hverju hlutf�ll skipta m�li");
begin_main_frame();
loggedinorreturn();

begin_frame('Hva� eru hlutf�ll?');
echo '
Hlutf�ll er m�likvar�i � hve miki� notandinn deilir og ni�urhalar. �au eru reiknu� me� �v� a� taka deilimagni� og deila �v� me� 
ni�urhalsmagninu �ar sem 1.0 merkir engan mun, minna en 1.0 merkir a� ni�urhali� er meira og h�rra en 1.0 ���ir a� deilimagni� er 
h�rra.
';
end_frame();
begin_frame('Af hverju eru� �i� a� sp� � hlutf�ll?');
echo '
Til a� samf�lagi� virki �urfa sem flestir a� vera virkir � �v� a� leggja eitthva� af m�rkum. Ef �a� er ekki gert og flestir gera 
ekkert anna� en a� vera byr�i, mun �a� deyja. Hlutf�llin eru einmitt til �ess a� sj� hve virkur hver notandi er.

Vi� ver�um samt a� gefa n�li�um t�kif�ri � a� a�lagast og �ess vegna eru �eir ekki banna�ir fyrir hlutf�ll fyrr 
en �eir hafa veri� me�limir � a� minnsta kosti 2 vikur. Til a� banna ekki notendur �taf engu og auka markt�knina, er skilyr�i� l�ka 
a� notandinn hafi n�� � 2 g�gab�ti af g�gnum. Einnig skiljum vi� a� notendur geti haft sl�m t�mabil �r�tt fyrir a� vera komnir vel inn � samf�lagi� en 
0.2 og l�gra eru m�rkin og eru allir notendur innan �eirra marka banna�ir um lei� og umsj�narf�lk vefsins s�r hlutf�llin.
';
end_frame();
begin_frame('Hva� um notendur sem halda s�r r�tt fyrir ofan 0.2 til a� ver�a ekki banna�ir?');
echo '
Stj�rnendur eru alltaf a� leita lei�a til �ess a� hvetja notendur til �ess a� halda s�r sem h�st me� �v� til d�mis l�ta �� vita af 
�framleg�inni og takmarka m�guleika notenda � kerfinu eftir �v� sem �eir hafa l�gri hlutf�ll. Notendur me� 0.75 e�a h�rra �urfa ekki 
a� b��a eftir n�jum deiliskr�m; �eir sem hafa 0.5 til 0.75 b��a � 12 t�ma og �eir sem hafa minna en 0.5 f� 24 klst � bi�.<br />
<br />
Alltaf er veri� a� b�ta vi� n�jum m�guleikum � kerfi� og eru l�xus-m�guleikarnir eing�ngu notanlegir af notendum sem uppfylla 
�kve�in skilyr�i, me�al annars um �kve�in hlutf�ll.
';
end_frame();
begin_frame('Hva� um notendur sem hafa g�� hlutf�ll en brj�ta reglurnar?');
echo '
Allir notendur sem f� vi�v�run munu lenda � �v� a� f� bi� eftir n�jum deiliskr�m eins og �eir v�ru me� minna en 0.5 � hlutf�ll � 
me�an vi�v�runart�mabili� varir.
';
end_frame();
begin_frame('Hj�lp, �g er me� sl�m hlutf�ll, hvernig b�ti �g mig?');
echo '
Til eru 2 lei�ir:<br />
1. Finna einhvern hlut sem �� �tt og heldur a� a�rir h�r � samf�laginu hafi �huga � a� n� �. Til eru lei�beiningar � spjallbor�inu um 
hvernig � a� b�a til deiliskr�r (torrent) me� �kve�num forritum.<br />
<br />
2. �egar �� hefur n�� � eitthva�, haltu glugganum opnum � me�an a�rir halda �fram a� n� � skr�nna. Hlutfalli� �itt mun s��an h�kka 
eftir �v� sem �� ert lengur og eftir �v� hve margir eiga eftir a� n� � �etta eftir a� �� kl�rar. Athuga�u samt sem ��ur a� 
ni�urhali� � deiliskr�num sem �� �tlar a� deila me� �essari a�fer� mun vinna gegn ��r � byrjun en vinnst upp ef �� n�r� yfir 1.0 � 
hlutf�llum fyrir �essa skr�. �a� er samt gott a� nota �essa a�fer� ef �� �arft endilega a� n� � �kve�i� efni.
';
end_frame();
end_main_frame();
stdfoot();
?>
