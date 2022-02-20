
#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid     = "Dialog-E5573";
const char* password = "d9d3baqd";

const char* host = "https://iot.uchdevelopment.com/message";
const String key = "iot_key_esp32#20220219";
const String decrptKey = "4a6add7ce3cde4a2b83821d4bf148dfb";
String topics = "";

void setup()
{
  WPAConnect();
  topics = curlRequest("/topics", "GET", "");
  Serial.println("Availble topics ->" + topics);
}


String curlRequest(String topic, String _method, String _data)
{
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String messageUrl = host + topic;
    http.begin(messageUrl);
    http.addHeader("x-api-key", decrptKey);
    Serial.println("request send to ->" + messageUrl);
    int httpCode = 0;
    if (_method == "POST") {
      http.addHeader("Content-Type", "application/x-www-form-urlencoded", false, true);
      httpCode = http.POST(_data);
    } else {
      http.addHeader("Content-Type", "application/json");
      httpCode = http.GET();
    }

    if (httpCode > 0) {
      if (httpCode == 200) {
        return http.getString();
      }
    }
  }

  return "no-content";
}

void WPAConnect() {

  Serial.begin(115200);
  delay(500);
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WPAPersonal Wi-Fi");
  while (WiFi.status() != WL_CONNECTED)
  {
    Serial.print(".");
    delay(200);
  }
  Serial.println();
  Serial.print("Connected with IP: ");
  Serial.println(WiFi.localIP());
  Serial.println();
}

void loop()
{
  String payload = "";
  payload = curlRequest("/room", "GET", "");
  Serial.println("response from api ->" + payload);
  payload = curlRequest("/room", "POST", "data=hello from client post data");
  Serial.println("response from api ->" + payload);
}
