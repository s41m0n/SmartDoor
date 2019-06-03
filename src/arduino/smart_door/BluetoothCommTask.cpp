#include "BluetoothComm.h"

extern String incomingBluetooth;

BluetoothComm::BluetoothComm() {
}

void BluetoothComm::init(int period) {
  Task::init(period);
}

//If there is no massage available, the global string is set to ""
//This is extremely safe due to the fact that the tasks are initialized and inserted into the 
//scheduler with a specific order.
//It avoids clearing the string at every state of the SmartDoor task.
void BluetoothComm::tick() {
  if (MsgServiceBluetooth.isMsgAvailable()) {
    Msg* msg = MsgServiceBluetooth.receiveMsg();
    incomingBluetooth = msg->getContent();
    delete msg;
  } else incomingBluetooth = "";  
}

