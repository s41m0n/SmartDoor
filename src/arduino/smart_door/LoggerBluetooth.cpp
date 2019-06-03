#include "LoggerBluetooth.h"
#include "MsgServiceBluetooth.h"

void LoggerServiceBluetooth::log(const String& msg) {
  MsgServiceBluetooth.sendMsg(msg);
}

