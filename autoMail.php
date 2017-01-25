<?php

$db = new mysqli('localhost', 'root', '', 'plastmar_aurokey');
$db->set_charset("utf8");
if ($db->connect_errno) {
    die('Cannot connect to database');
}


if(!isset($_GET['orderId'])&&!isset($_GET['mark'])) {
    $statement = $db->query('select o.order_id,CONCAT(o.firstname," ",o.lastname) as name,o.email,o.date_added FROM oc_order as o
where DATEDIFF(now(),o.date_added)=0
and o.order_status_id = 5
');
$subject = "Оценка качеството на услугите на VIMAX";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

    while ($row = $statement->fetch_assoc()) {
        mail($row[email],$subject, sendForm(row[name],row[customer_id],row[order_id]),$headers);
    }

}
else {



    $orderId= $_GET['orderID'];
    $mark =  $_GET['mark'];
//    INSERT INTO feedback (customer_name,customer_email,order_id,mark) VALUES
//    ((Select CONCAT(o.firstname," ",o.lastname) as name from oc_order as o where o.order_id =28 ),
//(Select o.email  from oc_order as o where o.order_id =28)
//,28,5 )


     $getName = $db->query('Select CONCAT(o.firstname," ",o.lastname) as name from oc_order as o where o.order_id ='.$orderId);
     $rowOne = $getName->fetch_assoc();
     $customerName = $rowOne[name];
     $getEmail = $db->query('Select o.email  from oc_order as o where o.order_id ='.$orderId);
     $rowTwo = $getName->fetch_assoc();
     $customerEmail = $rowTwo[email];


    $statement = $db->prepare("INSERT INTO feedback (customer_name,customer_email,order_id,mark) VALUES (?,?,?,?)");

    $statement->bind_param("ssii",$customerName,$customerEmail,$orderId,$mark);

    $statement->execute();
    header('Location: '."http://vimax.bg/");
}





function sendForm($name,$orderId){

    $form='';
    $link ="http://localhost/public_html//autoMail.php?&orderID=".$orderId."&mark=";
    $form='<html xmlns="http://www.w3.org/1999/xhtml">
    
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Качество на предлаганите от Vimax услуги</title>
                                                                                                                                                                                                                                                                                                                                                                                                        
<style type="text/css">
	.ReadMsgBody {width: 100%; background-color: #ffffff;}
	.ExternalClass {width: 100%; background-color: #ffffff;}
	body	 {width: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;font-family: Georgia, Times, serif}
	table {border-collapse: collapse;}
	
	@media only screen and (max-width: 640px)  {
                    body[yahoo] .deviceWidth {width:440px!important; padding:0;}	
					body[yahoo] .center {text-align: center!important;}	 
			}
			
	@media only screen and (max-width: 479px) {
                    body[yahoo] .deviceWidth {width:280px!important; padding:0;}	
					body[yahoo] .center {text-align: center!important;}	 
			}

</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" style="font-family: Georgia, Times, serif; background:#F0ECDB" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td width="100%" valign="top" bgcolor="#F0ECDB" style="padding-top:0px">
			<table width="580"  class="deviceWidth" border="0" cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td valign="top" style="padding:30px 0 20px 0" align="left">
						<a href="http://vimax.bg/?email-pool" target="_blank"><img  class="deviceWidth" src="http://vimax.bg/image/newsletters/order-poll/logo.png" alt="" border="0" style="display: block; width:200px" /></a>						
					</td>
				</tr>      
			</table>
			<table width="580"  class="deviceWidth" border="0" cellpadding="0" cellspacing="0" align="center" style="border:1px solid #847F1A; border-collapse: separate; border-radius:4px; background:#f4f4f4;">
				<tr>
					<td valign="top" style="padding:20px 0 0 20px; color: #847F1A; font-size: 20px; font-family: Verdana;" align="left">
                    Здравейте'." ".$name.'			
					</td>
				</tr>
				<tr>
					<td valign="top" style="padding:20px 0 0 20px; color: #434343; font-size: 16px; font-family: Verdana;" align="left">
                    Благодарим за направената поръчка на Vimax. 				
					</td>
				</tr>
				<tr>
					<td valign="top" style="padding:5px 20px; font-family: Verdana; font-size: 16px; color: #3F3F38; text-align:justify;" align="center" >
                    Бихме желали да Ви помолим да ни поставите оценка за качеството на извършените от нас услуги.<br>
											
					</td>
				</tr>
				<tr>
					<td valign="top" style="padding:20px; font-family: Verdana; font-size: 16px; color: #847F1A; text-align:justify;" align="center" >
						<strong>Доволни ли останахте - оценете от 1 до 5</strong>		
					</td>
				</tr>
				<tr>
					<td valign="top" style="padding:20px; font-family: Verdana; font-size: 16px; color: #3F3F38; text-align:center;" align="center" >
						<a target="_blank" href="'.$link."1". '"><img src="http://vimax.bg/image/newsletters/order-poll/emo1.png"></a>
						<a target="_blank" href="'.$link."2". '"><img src="http://vimax.bg/image/newsletters/order-poll/emo2.png"></a>		
						<a target="_blank" href="'.$link."3". '"><img src="http://vimax.bg/image/newsletters/order-poll/emo3.png"></a>		
						<a target="_blank" href="'.$link."4". '"><img src="http://vimax.bg/image/newsletters/order-poll/emo4.png"></a>		
						<a target="_blank" href="'.$link."5". '"><img src="http://vimax.bg/image/newsletters/order-poll/emo5.png"></a>								
					</td>
				</tr>
				<tr>
					<td valign="top" style="padding:20px; font-family: Verdana; font-size: 18px; color: #847F1A; text-align:left;" align="left" >
                    Успешен ден,<br>
                екипът на Vimax
                </td>
				</tr>						
				
			</table>
          

			<table width="580" border="0" cellpadding="0" cellspacing="0" align="center" style="margin:30px auto; border-collapse: separate; border-radius:4px;">
				<tr>
					<td >
						<table width="580" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
                            <tr>
                                 <td valign="top" style="font-size: 10px; color: #000000; font-family: Verdana, sans-serif; padding:20px" class="center" align="center">
									<em>Вимакс България ООД, Всички права запазени.</em><br>
                Официален уеб сайт на фирмата: <a href="http://vimax.bg/?email-pool" target="_blank" style="color:#d71624;">www.vimax-karcher.com</a><br>
<br>
<strong>Официален емайл:</strong><br>
<a href="mailto:sales@vimax.bg" target="_blank" style="color:#d71624;">sales@vimax.bg</a><br>
<br>
                Уведомяваме Ви, че съгласно Закона за електронна търговия (чл. 6, ал. 1) е възможно това да е непоискано търговско съобщение.Това писмо може да бъде разпознато катонепоискано търговско. Молим да ни извините ако сме Ви причинили неудобство. Ако нежелаете да получавате повече съобщения от нас, отговорете на този имейл като в кликнете <a href="mailto:sales@vimax.bg?subject=Отписване на email" target="_blank" style="color:#d71624;">тук</a>.
                        		</td>
                        	</tr>
                        </table>                  						
                    </td>
                </tr>
            </table>
						
		</td>
	</tr>
</table>

</body>
</html>';
    return $form;


}