package embedded.smartdoor;

/**
 * Created by Simone on 09/02/18.
 */

//Public class containing all the parameters and settings of the system.
public class Settings {

    static final String LOG_TAG = "LOGTAG";
    static final String TARGET_BT_DEVICE_NAME = "DEVICENAME";
    static final String TARGET_BT_DEVICE_UUID = "DEVICEUUID";

    static final int ENABLE_BT_REQUEST = 1;
    static final int REQUEST_OPEN_DOOR = 2;
    static final int LOGGED = 3;

    static final String WELCOME = "welcome";
    static final String LOGIN_OK = "ok";
    static final String LOGIN_KO = "ko";
    static final String NEED_USER = "reg";
    static final String NEED_CONFIRM = "conf";
    static final String INSIDE = "inside";
    static final String FAILED = "failed";
    static final String LOGOUT = "logout";
    static final String STAY = "stay";
}
