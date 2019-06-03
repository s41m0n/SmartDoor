#ifndef __MSGSERVICEBLUETOOTH__
#define __MSGSERVICEBLUETOOTH__

#include "Arduino.h"
#include "SoftwareSerial.h"
#include "Msg.h"

//Service used by BluetoothCommTask to read from the software serial
//and from the LoggerBluetooth to send messages to the software serial
class MsgServiceBluetoothClass {

  public:
    void init(int rxPin, int txPin);
    bool isMsgAvailable();
    Msg* receiveMsg();
    void sendMsg(const String& msg);
    
  private:
    String content;
    SoftwareSerial* channel;
};

extern MsgServiceBluetoothClass MsgServiceBluetooth;

#endif

