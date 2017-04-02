function SendToDevice($title, $message, $regId)
{
    require_once('TestNotifications.php');
    // Message payload, using data from post request
    $msg_payload = array(
        'mtitle' => $title,
        'mdesc' => $message,
    );
    $var = new PushNotifications();
    $var->android($msg_payload, $regId);
}
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && (!empty($_POST['SendTo']))) {
    SendToDevice($_POST['title'], $_POST['messageBody'], $_POST['SendTo']);
}

<!-- HTML Form that contains input fields to send notification data in POST request on submit -->
<!DOCTYPE HTML>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<h1>Test FCM from server to client! </h1>
</header>

<form method="POST" action = "TestNotifications.php">
    Who do you want to send the alert to?
    <select id = "SendTo" name="SendTo">
<!-- Put the FCM registration token in option value  --> 
        <option value="FCM registration token">Device 1</option>
    </select>
    <br />
    Title: <input tye="text" name ="title" id="title">
    <br />
    Message Body : <input type = "text", name = "messageBody">
    <br />
    <input type="submit" name="submit" id="submit">
</form>
</body>
</html>
