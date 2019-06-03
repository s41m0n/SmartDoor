#include "Arduino.h"
#include "MsgServiceSerial.h"

MsgServiceSerialClass MsgServiceSerial;

String content = "";

void serialEvent() {
  /* reading the content */
  while (Serial.available()) {
    char ch = (char) Serial.read();
    if (ch == '\n') {
      MsgServiceSerial.currentMsg = new Msg(content);
      MsgServiceSerial.msgAvailable = true;
    } else {
      content += ch;
    }
  }
}

bool MsgServiceSerialClass::isMsgAvailable() {
  return msgAvailable;
}

Msg* MsgServiceSerialClass::receiveMsg() {
  if (msgAvailable) {
    Msg* msg = currentMsg;
    msgAvailable = false;
    currentMsg = NULL;
    content = "";
    return msg;
  } else {
    return NULL;
  }
}

void MsgServiceSerialClass::init() {
  Serial.begin(9600);
  while(!Serial) {};
  content.reserve(256);
  content = "";
  currentMsg = NULL;
  msgAvailable = false;
}

void MsgServiceSerialClass::sendMsg(const String& msg) {
  Serial.println(msg);
}

