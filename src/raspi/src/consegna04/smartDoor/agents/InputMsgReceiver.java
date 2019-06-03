package consegna04.smartDoor.agents;

import consegna04.smartDoor.common.BasicController;

import consegna04.smartDoor.common.BasicEventLoopController;
import consegna04.smartDoor.common.Serial;
import consegna04.smartDoor.event.Login;
import consegna04.smartDoor.event.LoginFailed;
import consegna04.smartDoor.event.LoginSuccess;
import consegna04.smartDoor.event.Logout;
import consegna04.smartDoor.event.NewIntensity;
import consegna04.smartDoor.event.NewTemperature;

public class InputMsgReceiver extends BasicController {

    private Serial serialDevice;
    private BasicEventLoopController gateway;

    public InputMsgReceiver(Serial serialDevice, BasicEventLoopController g) {
        this.serialDevice = serialDevice;
        this.gateway = g;
    }

    @Override
    public void run() {
        while (true) {
            try {
                String msg = serialDevice.waitForMsg();
                if (msg.equals("logout")) {
                    this.gateway.notifyEvent(new Logout());
                }else if(msg.substring(0, 1).equals("T")) {
                    this.gateway.notifyEvent(new NewTemperature(msg.substring(1, msg.length())));
                }else if(msg.substring(0, 1).equals("K")) {
                    this.gateway.notifyEvent(new NewIntensity(msg.substring(1, msg.length())));
                }else if(msg.equals("inside")) {
                    this.gateway.notifyEvent(new LoginSuccess());
                }else if (msg.equals("failed")) {
                    this.gateway.notifyEvent(new LoginFailed());
                } else {
                    String[] tmp = msg.split("\\s+");
                    this.gateway.notifyEvent(new Login(tmp[0], tmp[1]));
                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

}
