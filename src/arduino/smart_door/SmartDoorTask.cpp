#include "Arduino.h"
#include "SmartDoor.h"
#include "LoggerSerial.h"
#include "LoggerBluetooth.h"
#include "Settings.h"

//The two global string to communicate
extern String incomingBluetooth;
extern String incomingSerial;

//Constructor: every component is initialized
SmartDoor::SmartDoor() {
  this->state = REST;
  this->intensity = 0;
  this->timeSaved = millis();
  myServo.attach(SERVO_PIN);
  myServo.write(0);
  myTmp = new TempSensor(TEMP);
  mySonar = new Sonar(ECHO_PIN, TRIG_PIN);
  ledOn = new Led(LED_ON);
  ledValue = new LedExt(LED_VALUE, 0);
  btnExit = new ButtonImpl(BTN_EXIT);
  myPir = new Pir(PIR);
}

//The task is initialized and the ledOn is switched On, the systyem is ready.
void SmartDoor::init(int period) {
  Task::init(period);
  ledOn->switchOn();
}

void SmartDoor::sendTemperature() {
  if (millis() - this->timeSaved > TEMP_DELAY) {
    String temp = "T";
    temp += myTmp->readTemperature();
    LoggerBluetooth.log(temp);
    LoggerSerial.log(temp);
    this->timeSaved = millis();
  }
}

void SmartDoor::sendIntensity() {
  String tmp = "K" ;
  tmp += this->intensity;
  LoggerSerial.log(tmp);
}

bool SmartDoor::isUserNear() {
  if (mySonar->getDistance() <= MIN_DIST) {
    if (millis() - this->timeSaved > MIN_SEC) {
      return true;
    }
  } else this->timeSaved = millis();
  return false;
}

//In this method all the values are re-initialized 
//the system goes back to the initial state and 
//a logout message is sent to BluetoothDevice and SeriaDevice connected
void SmartDoor::logout() {
  myServo.write(0);
  this->intensity = 0;
  this->timeSaved = millis();
  ledValue->setIntensity(this->intensity);
  ledValue->switchOff();
  LoggerSerial.log(LOGOUT);
  LoggerBluetooth.log(LOGOUT);
  this->state = REST;
}

void SmartDoor::tick() {
  switch (this->state) {
    //In this state the system waits till the user come near
    case REST: {
        if (isUserNear()) {
          LoggerBluetooth.log(WELCOME_STR);
          this->state = LOGGING;
        }
        break;
      }
    //In this state the user must keep staying in front of the door
    //and must send his credential.
    case LOGGING: {
        if (isUserNear()) {
          if (incomingBluetooth != "") {
            LoggerSerial.log(incomingBluetooth);
            this->state = CHECKING;
          }
        } else {
          LoggerBluetooth.log(STAY);
          this->state = REST;
        }
        break;
      }
    //If the gateway says that the user is allowed to enter, the door is opened
    case CHECKING: {
        if (incomingSerial != "") {
          if (incomingSerial == LOGIN_OK) {
            myServo.write(180);
            this->timeSaved = millis();
            this->state = OPENING;
          } else {
            this->state = LOGGING;
          }
          LoggerBluetooth.log(incomingSerial);
        }
        break;
      }
    //If the user doesn't enter in time, the door is closed
    case OPENING: {
        if (myPir->isDetected()) {
          LoggerBluetooth.log(INSIDE);
          LoggerSerial.log(INSIDE);
          ledValue->switchOn();
          this->timeSaved = 0;
          this->state = LOGGED;
        } else if (millis() - this->timeSaved > MAX_DELAY) {
          myServo.write(0);
          this->state = LOGGING;
          this->timeSaved = millis();
          LoggerBluetooth.log(USER_FAILED);
          LoggerSerial.log(USER_FAILED);
        }
        break;
      }
    //In this state is checked if btnExit is pressed or if the user kills the application into the BluetoothDevice
    //Every cases are verified.
    //Also the temperature and the intensity are keeped updated
    case LOGGED: {
        sendTemperature();
        if (btnExit->isPressed()) logout();
        if (incomingBluetooth != "") {
          int incomingData = incomingBluetooth.toInt();
          if (incomingData == 0) {
            logout();
          }
          else {
            this->intensity = incomingData - 1;
            sendIntensity();
            ledValue->setIntensity(map(this->intensity, 0, 100, 0, 255));
          }
        }
        break;
      }
  }
}
