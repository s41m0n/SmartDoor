#include "TempSensor.h"
#include "Arduino.h"

TempSensor::TempSensor(int pin) {
  this->pin = pin;
  pinMode(pin, INPUT);
}

float TempSensor::readTemperature() {
  int valore = analogRead(this->pin);
  float temp = valore * 0.48875;
  return temp;
}
