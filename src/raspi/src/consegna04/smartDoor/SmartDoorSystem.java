package consegna04.smartDoor;

import consegna04.smartDoor.devices.Led;

import consegna04.smartDoor.devices.Light;
import consegna04.smartDoor.common.Serial;
import consegna04.smartDoor.devices.SerialImpl;
import consegna04.smartDoor.agents.Gateway;
import consegna04.smartDoor.agents.InputMsgReceiver;

//The main class.
//Here are created all the object I need.
public class SmartDoorSystem {

    private static final int LED_INSIDE = 0;
    private static final int LED_FAILED = 2;
    private static final int BOUND_RATE = 9600;
    
    public static void main(String[] args) throws Exception {
        //The two leds
        Light ledInside = new Led(LED_INSIDE);
        Light ledFailed = new Led(LED_FAILED);
        
        //The SerialDevice (Event Loop serial)
        Serial serialDevice = new SerialImpl(args[0], BOUND_RATE);
        
        //The gateway, my FSM
        Gateway g = new Gateway(ledInside, ledFailed, serialDevice);
        //The InputReceiver, responbile of parsing input from serial
        InputMsgReceiver rec = new InputMsgReceiver(serialDevice, g);
        g.start();
        rec.start();
    }

}
