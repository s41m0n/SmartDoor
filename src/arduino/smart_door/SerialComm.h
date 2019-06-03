#ifndef __SERIAL_COMM__
#define __SERIAL_COMM__

#include "Task.h"
#include "MsgServiceSerial.h"

//Task for Communication with RASPBERRY!
class SerialComm: public Task {

  public:
    SerialComm();
    void init(int period);
    void tick();

};

#endif

