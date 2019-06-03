#include "Arduino.h"
#include "MsgServiceBluetooth.h"

MsgServiceBluetoothClass MsgServiceBluetooth;

void MsgServiceBluetoothClass::init(int rxPin, int txPin){
  channel = new SoftwareSerial(rxPin, txPin);
  channel->begin(9600);
  content.reserve(256);
  content = "";
}

void MsgServiceBluetoothClass::sendMsg(const String& msg){
  channel->println(msg);  
}

bool MsgServiceBluetoothClass::isMsgAvailable(){
  return channel->available();
}

Msg* MsgServiceBluetoothClass::receiveMsg(){
  if (channel->available()){    
    content="";
    while (channel->available()) {
      content += (char)channel->read();      
    }
    return new Msg(content);
  } else {
    return NULL;  
  }
}




