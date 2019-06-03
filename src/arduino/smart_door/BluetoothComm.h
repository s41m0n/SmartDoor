#ifndef __BLUETOOTH_COMM__
#define __BLUETOOTH_COMM__

#include "Task.h"
#include "MsgServiceBluetooth.h"

//Task for Communication with ANDROID!
class BluetoothComm: public Task {

  public:
    BluetoothComm();
    void init(int period);
    void tick();

};

#endif

