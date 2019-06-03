#include "SerialComm.h"

extern String incomingSerial;

SerialComm::SerialComm() {
}

void SerialComm::init(int period) {
  Task::init(period);
}

//If there is no massage available, the global string is set to ""
//This is extremely safe due to the fact that the tasks are initialized and inserted into the 
//scheduler with a specific order.
//It avoids clearing the string at every state of the SmartDoor task.
void SerialComm::tick() {
  if (MsgServiceSerial.isMsgAvailable()) {
    Msg* msg = MsgServiceSerial.receiveMsg();
    incomingSerial = msg->getContent();
    delete msg;
  } else incomingSerial = "";
}

