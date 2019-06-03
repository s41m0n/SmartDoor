#ifndef __SETTINGS__
#define __SETTINGS__

//File with all the global settings of the system.

//String used to communicate
#define WELCOME_STR "welcome\n"
#define CLOSE_SESSION "close"
#define LOGIN_OK "ok"
#define USER_FAILED "failed"
#define INSIDE "inside"
#define LOGOUT "logout"
#define STAY "stay\n"

//Parameters
#define MIN_DIST 0.3
#define MIN_SEC 2000
#define MAX_DELAY 3000
#define TEMP_DELAY 5000

//Pins
#define SERVO_PIN 6
#define TRIG_PIN 10
#define ECHO_PIN 11
#define PIR 7
#define LED_ON 8
#define LED_VALUE 5
#define BTN_EXIT 12
#define TEMP A2

#endif
