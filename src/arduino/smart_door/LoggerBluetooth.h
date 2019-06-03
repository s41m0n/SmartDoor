#ifndef __LOGGERBLUETOOTH__
#define __LOGGERBLUETOOTH__

#include "Arduino.h"

//The static class responbile of logging (sending) messages to the Bluetooth device connected
class LoggerServiceBluetooth {

  public:
    void log(const String& msg);
};

extern LoggerServiceBluetooth LoggerBluetooth;

#endif

