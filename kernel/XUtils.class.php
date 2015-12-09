<?php


class XUtils {

    function DaysInMonth( $month, $year) {

        if ( in_array($month, array(1,3,5,7,8,10,12) ) ) {
            return 31;
        } else if ($month == 2 ) {
            if ( IsLeap($year) ) return 29;
            else return 28;
        } else {
            return 30;
        }
    }

    function IsLeap($year) {
        if ( !($year % 4) && ( $year % 100 || !($year % 400) ) ) {
            return true;
        } else {
            return false;
        }
    }

    function sendMail( $email, $subject, $message, $name) {

        global $app;
        $headers = "To: ".XUtils::strToBase($name,'utf-8', 'windows-1251')." <$email>\n";
        $headers .= "From: ".XUtils::strToBase($app->Dictionary->getWord("MailFrom"),'utf-8', 'windows-1251')."<".$app->getVar("mailsender").">\n";
        $headers .= "X-Sender: ".XUtils::strToBase($app->Dictionary->getWord("XSender"),'utf-8', 'windows-1251')."\n";
        $headers .= "Content-Type: text/plain; charset=windows-1251\n";
        $headers .= "Content-Transfer-Encoding: 8bit\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers = iconv( "utf-8", "windows-1251", $headers );
        $subject = XUtils::strToBase($subject,'utf-8', 'windows-1251');
        $message = iconv( "utf-8", "windows-1251", $message );

        mail($email,$subject,$message,$headers);
    }

    function strToBase( $str, $incharset, $tocharset) {
        $str = iconv( $incharset, $tocharset, $str );
        return '=?'.$tocharset.'?B?'.base64_encode($str).'?=';
    }

    function getCode() {
        return md5( rand().microtime().rand() );
    }
}


function template_callback($reg)
{
    global $app;
    $word = $app->Dictionary->getWord( $reg[2] );
    if ($word == "NULL")
    {
        // echo strtolower($reg[2])."\n";
    }
    // return str_replace(" ","&#xA0;",$word);
    // return $app->SiteDecode($word);
    return $word;
}

?>