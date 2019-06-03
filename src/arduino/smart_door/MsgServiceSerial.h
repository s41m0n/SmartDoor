#ifndef __MSGSERVICESERIAL__
#define __MSGSERVICESERIAL__

#include "Arduino.h"
#include "Msg.h"

//Service used by SerialCommTask to read incoming massages from the serial port
//and by LoggerSerial to send messages into the serial port.
class MsgServiceSerialClass {

  public:
  
    Msg* currentMsg;
    bool msgAvailable;
    void init();
    bool isMsgAvailable();
    Msg* receiveMsg();
    void sendMsg(const String& msg);
};

extern MsgServiceSerialClass MsgServiceSerial;

#endif

