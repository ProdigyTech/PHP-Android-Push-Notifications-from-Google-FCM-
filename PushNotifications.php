class PushNotifications{
    private  static  $API_ACCESS_KEY = "YOUR_FCM_SERVER_KEY ";
    public function __construct() {
       // exit('Init function is not allowed');
   }
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
