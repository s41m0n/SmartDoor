#include "LoggerSerial.h"
#include "MsgServiceSerial.h"

void LoggerServiceSerial::log(const String& msg) {
  MsgServiceSerial.sendMsg(msg);
}

