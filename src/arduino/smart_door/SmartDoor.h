#ifndef __DOOR__
#define __DOOR__

#include "Task.h"
#include "Servo.h"
#include "Sonar.h"
#include "LedExt.h"
#include "Pir.h"
#include "ButtonImpl.h"
#include "TempSensor.h"

//The main task of the system.
//Here are declared every component of the system and the main methods used.
class SmartDoor: public Task {

  public:
  SmartDoor();
  void init(int period);
  void tick();
  
  private:
  //5 states
  //REST -> the system is waiting for the user to come near
  //LOGGING ->the system is waiting for the user to login (send username and password)
  //CHECKING -> the system is waiting for a Gateway (Raspy) response
  //OPENING -> the user logged successfully, but he needs to enter into the room in time.
  //LOGGED -> the user entered into the room and now he's in.
  enum { REST, LOGGING, CHECKING, OPENING, LOGGED } state;
  Servo myServo;
  Sonar* mySonar;
  Led* ledOn;
  LightExt* ledValue;
  Pir* myPir;
  Button* btnExit;
  TempSensor* myTmp;
  int intensity;  //ledValue intensity
  unsigned long timeSaved; //Variable used to check the time exceeded
  bool isUserNear(); //Method to check if the user come near the door and stays there for X seconds
  void sendTemperature(); //Method to send the temperature to Bluetooth Device and to Serial Device every X seconds.
  void sendIntensity(); //Method to send the new intensity to the Serial Device.
  void logout(); //Method to logout from the system.
};

#endif
