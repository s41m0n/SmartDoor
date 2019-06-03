#ifndef __LOGGERSERIAL__
#define __LOGGERSERIAL__

#include "Arduino.h"

//The static class responbile of logging (sending) messages to the Serial device connected
class LoggerServiceSerial {

  public:
    void log(const String& msg);
};

extern LoggerServiceSerial LoggerSerial;

#endif

