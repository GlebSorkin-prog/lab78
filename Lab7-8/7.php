<?php
set_time_limit(1200);
$mail1 = "<glebalso@mail.ru>";
$tmail1 = htmlspecialchars($mail1);

if (!empty($_POST) && !isset($sent)) {

    $emailer_subj = $_POST['emailer_subj'];
    $emailer_mails = $_POST['emailer_mails'];
    $emailer_text = $_POST['emailer_text'];
    $emailer_yourmail = $_POST['emailer_yourmail'];

    if (empty($emailer_subj) || $emailer_subj == "Тема письма") {
        $mail_msg = '<b>Вы не ввели тему письма</b>';
    } elseif (empty($emailer_mails) || $emailer_mails == "Почтовые адреса") {
        $mail_msg = '<b>Не указано адреса получателей</b>';
    } elseif (empty($emailer_text) || $emailer_text == "Текст письма") {
        $mail_msg = '<b>Вы не ввели текст письма</b>';
    } else {
        $mail_msg = 'Ваше сообщение отправлено.<br>Нажмите <a href="' . $_SERVER['REQUEST_URI'] . '">здесь</a>, если ваш браузер не поддерживает перенаправление.';
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: $emailer_yourmail";
        $emailer_text = preg_replace("/ +/", " ", $emailer_text);
        $emailer_text = preg_replace("/(\r\n){3,}/", "\r\n\r\n", $emailer_text);
        $emailer_text = str_replace("\r\n", "<br>", $emailer_text);
        $emails = explode(",", $emailer_mails);
        $count_emails = count($emails);
        for ($i = 0; $i <= $count_emails - 1; $i++)
        {
            $email = trim($emails[$i]);
            if ($emails[$i] != "") {
                mail($email, $emailer_subj, $emailer_text, $headers);
                sleep(5);
            }
        }
        $sent = 1;
    }
} else {
    $mail_msg = 'Все поля обязательны для заполнения.';
    $emailer_text = $emailer_subj = $emailer_mails = $emailer_yourmail = '';
}
$text= "";
if (!isset($sent)) {
    if (isset($_GET['messent'])) {
        echo $text .= "<b style=\"text-align:center;margin-top:200px;display:block;\">Всё окей. Сообщение отправлено. <a href=\"emailer.php\">Ещё?</a><br><br><u>Отчёт:</u></b> <ol style=\"display:block;width:300px;margin:10px auto;\">";
        readfile("log.txt");
        echo "</ol>";
    } else {
        echo $text .= <<<post
    <script type="text/javascript">
    function form_validator(form) {
    if (form.emailer_subj.value=='' || form.emailer_subj.value=='Тема письма') { alert('Укажите тему письма.'); form.emailer_subj.focus(); return false; }
    if (form.emailer_mails.value=='' || form.emailer_mails.value=='Почтовые адреса') { alert('Укажите адреса получаталей.'); form.emailer_mails.focus(); return false; }
    if (form.emailer_text.value=='' || form.emailer_text.value=='Текст письма') { alert('Вы не заполнили поле сообщения.'); form.emailer_text.focus(); return false; }
    return true;
    }
    </script>
    <style type="text/css" >
 body { background: url("bg.png"); }
    form {display:block;margin:20px auto;width:500px;}
    textarea, input, select {width:100%; margin:5px 0;}
    textarea {height:200px;}
    .red {color:#fff;}
    </style>
    <form method="post" onsubmit="return form_validator(this);">
    <p class="red">$mail_msg</p>
    <input type="text" name="emailer_subj" id="emailer_subj" value="Тема письма" title="Тема письма?" onfocus="if (this.value=='Тема письма') this.value='';" onblur="if (this.value=='') this.value='Тема письма';">
    <textarea name="emailer_mails" id="emailer_mails" title="Кто получатели?" onfocus="if (this.value=='Почтовые адреса') this.value='';" onblur="if (this.value=='') this.value='Почтовые адреса';">Почтовые адреса</textarea>
    <textarea name="emailer_text" id="emailer_text" title="Что пишем?" onfocus="if (this.value=='Текст письма') this.value='';" onblur="if (this.value=='') this.value='Текст письма';">Текст письма</textarea>
    <select name="emailer_yourmail">
    <option value="$mail1">$tmail1</option>
    </select>
    <input type="submit" value="Отправить" title="Отправить мыл">
    </form>
post;
    }
} else {
    $ret_uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Refresh: 0; URL=http://" . $ret_uri . "?messent");
    exit;
}
?>

