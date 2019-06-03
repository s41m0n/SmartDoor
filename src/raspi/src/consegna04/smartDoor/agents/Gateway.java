package consegna04.smartDoor.agents;

import consegna04.smartDoor.common.BasicEventLoopController;
import consegna04.smartDoor.common.Serial;
import consegna04.smartDoor.devices.Light;
import consegna04.smartDoor.event.Event;
import consegna04.smartDoor.event.Login;
import consegna04.smartDoor.event.LoginFailed;
import consegna04.smartDoor.event.LoginSuccess;
import consegna04.smartDoor.event.Logout;
import consegna04.smartDoor.event.NewIntensity;
import consegna04.smartDoor.event.NewTemperature;

import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class Gateway extends BasicEventLoopController {

    private enum State {
        LOGGING, LOGGED
    };

    private State state;
    private Light ledInside;
    private Light ledFailed;
    private Serial serialDevice;
    private DBConnect connect = new DBConnect();
    private String username;

    private static String NO_USER = "reg";
    private static String LOGIN_KO = "ko";
    private static String LOGIN_OK = "ok";
    private static String NEED_CONFIRM = "conf";
    private static final int FLASH_TIME = 200;

    public Gateway(Light ledInside, Light ledFailed, Serial serialDevice) {
        this.ledInside = ledInside;
        this.ledFailed = ledFailed;
        this.serialDevice = serialDevice;
        this.state = State.LOGGING;
    }

    @Override
    protected void processEvent(Event ev) {
        try {
            switch (this.state) {
            case LOGGING: {
                if (ev instanceof Login) {
                    User user = connect.getUser(((Login) ev).getUsername());
                    if (user == null) {
                        serialDevice.sendMsg(NO_USER);
                    } else {
                        String tmpPass = this.getCryptoPassword(((Login) ev).getPassword());
                        tmpPass = getCryptoPassword(tmpPass + user.getSalt());
                        if (tmpPass.equals(user.getPassword())) {
                            if (user.isConfirmed()) {
                                this.username = ((Login) ev).getUsername();
                                serialDevice.sendMsg(LOGIN_OK);
                                this.state = State.LOGGED;
                                return;
                            } else {
                                serialDevice.sendMsg(NEED_CONFIRM);
                            }
                        } else {
                            serialDevice.sendMsg(LOGIN_KO);
                        }
                        this.ledFailed.switchOn();
                        Thread.sleep(FLASH_TIME);
                        this.ledFailed.switchOff();
                    }
                }
                break;
            }
            case LOGGED: {
                if (ev instanceof Logout) {
                    connect.updateIntensity("0");
                    this.ledInside.switchOff();
                    this.state = State.LOGGING;
                } else if (ev instanceof NewTemperature) {
                    connect.updateTemperature(((NewTemperature) ev).getTemperature());
                } else if (ev instanceof NewIntensity) {
                    connect.updateIntensity(((NewIntensity) ev).getIntensity());
                } else if (ev instanceof LoginSuccess) {
                    connect.insertLog(this.username, "Success");
                    this.ledInside.switchOn();
                } else if (ev instanceof LoginFailed) {
                    connect.insertLog(this.username, "Failed");
                    this.username = "";
                    this.state = State.LOGGING;
                }
                break;
            }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }

    }

    private String getCryptoPassword(String passwordToHash) {
        String generatedPassword = null;
        try {
            MessageDigest md = MessageDigest.getInstance("SHA-512");
            byte[] bytes = md.digest(passwordToHash.getBytes(StandardCharsets.UTF_8));
            StringBuilder sb = new StringBuilder();
            for (int i = 0; i < bytes.length; i++) {
                sb.append(Integer.toString((bytes[i] & 0xff) + 0x100, 16).substring(1));
            }
            generatedPassword = sb.toString();
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        }
        return generatedPassword;
    }
}
