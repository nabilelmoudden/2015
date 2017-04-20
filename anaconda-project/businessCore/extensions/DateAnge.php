<?php

/**
 * Description of ArrayHelper
 *
 * @author JulienL
 */
class DateAnge
{
	/**
	 * 
	 * 
	 * 
	 */


static public function  nomange($dateange)
{

	//$dateange1= $m.$j;
	$angenom="";
	
	$m=date("m",strtotime($dateange));
	$j=date("d",strtotime($dateange));
	$dateange1= $m.$j;

	if(	101<= $dateange1 && 105>= $dateange1) { $angenom="Nemamiah";} ;
	if(	106<= $dateange1 && 110>= $dateange1) { $angenom="Yeialel";} ;
	if(	111<= $dateange1 && 115>= $dateange1) { $angenom="Harahel";} ;
	if(	116<= $dateange1 && 120>= $dateange1) { $angenom="Mitzrael";} ;
	if(	121<= $dateange1 && 125>= $dateange1) { $angenom="Umabel";} ;
	if(	126<= $dateange1 && 130>= $dateange1) { $angenom="Iah-Hel";} ;
	if(	131<= $dateange1 && 204>= $dateange1) { $angenom="Anauel";} ;
	if(	205<= $dateange1 && 209>= $dateange1) { $angenom="Mehiel";} ;
	if(	210<= $dateange1 && 214>= $dateange1) { $angenom="Damabiah";} ;
	if(	215<= $dateange1 && 219>= $dateange1) { $angenom="Manakel";} ;
	if(	220<= $dateange1 && 224>= $dateange1) { $angenom="Ayael";} ;
	if(	225<= $dateange1 && 229>= $dateange1) { $angenom="Habuiah";} ;
	if(	301<= $dateange1 && 305>= $dateange1) { $angenom="Rahael";} ;
	if(	306<= $dateange1 && 310>= $dateange1) { $angenom="Yamabiah";} ;
	if(	311<= $dateange1 && 315>= $dateange1) { $angenom="Haiaiel";} ;
	if(	316<= $dateange1 && 320>= $dateange1) { $angenom="Mumiah";} ;
	if(	321<= $dateange1 && 325>= $dateange1) { $angenom="Vehuiah";} ;
	if(	326<= $dateange1 && 330>= $dateange1) { $angenom="Yeliel";} ;
	if(	331<= $dateange1 && 404>= $dateange1) { $angenom="Sitael";} ;
	if(	405<= $dateange1 && 419>= $dateange1) { $angenom="Elemiah";} ;
	if(	410<= $dateange1 && 414>= $dateange1) { $angenom="Mahasiah";} ;
	if(	415<= $dateange1 && 420>= $dateange1) { $angenom="Lelahel";} ;
	if(	421<= $dateange1 && 425>= $dateange1) { $angenom="Achaiah";} ;
	if(	426<= $dateange1 && 430>= $dateange1) { $angenom="Kahetel";} ;
	if(	501<= $dateange1 && 505>= $dateange1) { $angenom="Haziel";} ;
	if(	506<= $dateange1 && 510>= $dateange1) { $angenom="Aladiah";} ;
	if(	511<= $dateange1 && 515>= $dateange1) { $angenom="Lauvuel";} ;
	if(	516<= $dateange1 && 520>= $dateange1) { $angenom="Hahaiah";} ;
	if(	521<= $dateange1 && 525>= $dateange1) { $angenom="Yezalel";} ;
	if(	526<= $dateange1 && 531>= $dateange1) { $angenom="Mebahel";} ;
	if(	601<= $dateange1 && 605>= $dateange1) { $angenom="Hariel";} ;
	if(	606<= $dateange1 && 610>= $dateange1) { $angenom="Hekamiah";} ;
	if(	611<= $dateange1 && 615>= $dateange1) { $angenom="Lauviah";} ;
	if(	616<= $dateange1 && 620>= $dateange1) { $angenom="Kaliel";} ;
	if(	621<= $dateange1 && 626>= $dateange1) { $angenom="Leuviah";} ;
	if(	627<= $dateange1 && 701>= $dateange1) { $angenom="Pahaliah";} ;
	if(	702<= $dateange1 && 706>= $dateange1) { $angenom="Nelkhael";} ;
	if(	707<= $dateange1 && 711>= $dateange1) { $angenom="Yeyayel";} ;
	if(	712<= $dateange1 && 716>= $dateange1) { $angenom="Melahel";} ;
	if(	717<= $dateange1 && 722>= $dateange1) { $angenom="Haheuiah";} ;
	if(	723<= $dateange1 && 727>= $dateange1) { $angenom="Nith-Haiah";} ;
	if(	728<= $dateange1 && 801>= $dateange1) { $angenom="Haaiah";} ;
	if(	802<= $dateange1 && 806>= $dateange1) { $angenom="Yeratel";} ;
	if(	807<= $dateange1 && 812>= $dateange1) { $angenom="Seheiah";} ;
	if(	813<= $dateange1 && 817>= $dateange1) { $angenom="Reyiel";} ;
	if(	818<= $dateange1 && 822>= $dateange1) { $angenom="Omael";} ;
	if(	823<= $dateange1 && 828>= $dateange1) { $angenom="Lekabel";} ;
	if(	829<= $dateange1 && 902>= $dateange1) { $angenom="Vasariah";} ;
	if(	903<= $dateange1 && 907>= $dateange1) { $angenom="Yehuiah";} ;
	if(	908<= $dateange1 && 912>= $dateange1) { $angenom="Lehahiah";} ;
	if(	913<= $dateange1 && 917>= $dateange1) { $angenom="Khavaquiah";} ;
	if(	918<= $dateange1 && 923>= $dateange1) { $angenom="Menadel";} ;
	if(	924<= $dateange1 && 928>= $dateange1) { $angenom="Aniel";} ;
	if(	929<= $dateange1 && 1003>= $dateange1) { $angenom="Haamiah";} ;
	if(	1004<= $dateange1 && 1008>= $dateange1) { $angenom="Rehael";} ;
	if(	1009<= $dateange1 && 1013>= $dateange1) { $angenom="Ieiazel";} ;
	if(	1014<= $dateange1 && 1018>= $dateange1) { $angenom="Hahahel";} ;
	if(	1019<= $dateange1 && 1023>= $dateange1) { $angenom="Mikael";} ;
	if(	1024<= $dateange1 && 1028>= $dateange1) { $angenom="Veuliah";} ;
	if(	1029<= $dateange1 && 1102>= $dateange1) { $angenom="Yelahiah";} ;
	if(	1103<= $dateange1 && 1107>= $dateange1) { $angenom="Sealiah";} ;
	if(	1108<= $dateange1 && 1112>= $dateange1) { $angenom="Ariel";} ;
	if(	1113<= $dateange1 && 1117>= $dateange1) { $angenom="Asaliah";} ;
	if(	1118<= $dateange1 && 1122>= $dateange1) { $angenom="Mihael";} ;
	if(	1123<= $dateange1 && 1127>= $dateange1) { $angenom="Vehuel";} ;
	if(	1128<= $dateange1 && 1202>= $dateange1) { $angenom="Daniel";} ;
	if(	1203<= $dateange1 && 1207>= $dateange1) { $angenom="Hahasiah";} ;
	if(	1208<= $dateange1 && 1212>= $dateange1) { $angenom="Imamiah";} ;
	if(	1213<= $dateange1 && 1216>= $dateange1) { $angenom="Nanael";} ;
	if(	1217<= $dateange1 && 1221>= $dateange1) { $angenom="Nithael";} ;
	if(	1222<= $dateange1 && 1226>= $dateange1) { $angenom="Mebahiah";} ;
	if(	1227<= $dateange1 && 1231>= $dateange1) { $angenom="Poyel";} ;


	return $angenom;
}
}
//$angenom=nomagne($jour, $mois_chiffre);
?>