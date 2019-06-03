#include "Scheduler.h"
#include "MsgServiceSerial.h"
#include "MsgServiceBluetooth.h"
#include "SmartDoor.h"
#include "BluetoothComm.h"
#include "SerialComm.h"

//Time for tasks and scheduler
#define TASK_BLUETOOTH 120
#define TASK_SERIAL 120
#define TASK_ROOM 120

//MCD of all tasks
#define SCHEDULER_TIME 60

//Pin for SoftwareSerial(Bluetooth)
#define RX 2
#define TX 3

//Global string to communicate between tasks
String incomingBluetooth = "";
String incomingSerial = "";

Scheduler sched;

void setup() {
  sched.init(SCHEDULER_TIME);

  MsgServiceSerial.init();
  MsgServiceBluetooth.init(RX, TX);

  //The main task used to manage the SmartDoor.
  SmartDoor* smartDoor = new SmartDoor();
  smartDoor->init(TASK_ROOM);

  //The task responbile of reading messages in the serial port.
  SerialComm* serialComm = new SerialComm();
  serialComm->init(TASK_SERIAL);

  //The task responsible of reading messages in the software serial (Bluetooth) port.
  BluetoothComm* bluetoothComm = new BluetoothComm();
  bluetoothComm->init(TASK_BLUETOOTH);

  sched.addTask(smartDoor);
  sched.addTask(serialComm);
  sched.addTask(bluetoothComm);
}

void loop() {
  sched.schedule();  
}
