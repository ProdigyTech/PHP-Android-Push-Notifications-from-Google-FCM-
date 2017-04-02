# Simple PHP script to send an Android Device push notifications using Firebase Cloud Messaging API

# Readme

$API_ACCESS_KEY: go to the firebase console, navigate to the project,  click the gear icon, project settings. Click on the tab "CLOUD MESSAGING"
should look like this:
![image](https://cloud.githubusercontent.com/assets/8797862/24583107/00d50e18-16f5-11e7-8015-366b2dd8de3d.png) **copy the server key from FCM and  store it in $API_ACCESS_KEY** 

**PushNotifications.php**
```
class PushNotifications{
    private  static  $API_ACCESS_KEY = "YOUR_FCM_SERVER_KEY ";
    public function __construct() {
       // exit('Init function is not allowed');
   }
```
   ```
 public function android($data, $reg_id) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $message = array
        (
            'title' => $data['mtitle'],
            'body' => $data['mdesc'],
            'subtitle' => '',
            'tickerText' => '',
            'msgcnt' => 1,
            'vibrate' => 1
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.self::$API_ACCESS_KEY
        );
        $fields = array(
            'notification' => $message,
            'to' => $reg_id,
        );
        return self::useCurl($url, $headers, $fields);
    }

```

```
private function useCurl($url, $headers , $fields) {
        $ch = curl_init();
        if ($url) {
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if ($fields) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            }
            // Execute post
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
       //checking the response code we get from fcm for debugging purposes
            echo "http response " . $httpcode;
       //checking the status/result of the push notif for debugging purposes
            echo $result;
            return $result;
        }

    }
}

```
`

**Test Notifications.php**

The SendToDevice Function is called once the server receives the POST request with the required parameters i.e After user submits the form
The function takes in a title, message, and a regID 

```

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

