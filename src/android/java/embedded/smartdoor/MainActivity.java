package embedded.smartdoor;

import android.app.Activity;
import android.app.AlertDialog;
import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.os.Handler;
import android.os.Message;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Gravity;
import android.widget.TextView;
import android.widget.Toast;

import java.lang.ref.WeakReference;
import java.util.UUID;

//This is the main activity actived when the application starts
public class MainActivity extends AppCompatActivity {

    //A bluetooth adapter used to find the pair devices
    private BluetoothAdapter btAdapter;
    //The bluetooth device target (I suppose that I already know the name of my HC-O6 and it's already pared)
    private BluetoothDevice targetDevice;

    private static MainActivityHandler uiHandler;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        setContentView(R.layout.activity_main);

        uiHandler = new MainActivityHandler(this);
    }

    //Method used to do something when an activity (Bt enable request) ends.
    @Override
    public void onActivityResult (int reqID , int res , Intent data ){
        if(reqID == Settings.ENABLE_BT_REQUEST && res == Activity.RESULT_OK){
            targetDevice = BluetoothUtils.findPairedDevice(Settings.TARGET_BT_DEVICE_NAME, btAdapter);

            if(targetDevice != null){
                ((TextView) findViewById(R.id.btFoundFlagLabel)).setText("Device Found: " + targetDevice.getName());
                connectToTargetBtDevice();
            }
        }

        if(reqID == Settings.ENABLE_BT_REQUEST && res == Activity.RESULT_CANCELED ){
            // BT enabling process aborted
        }
    }

    @Override
    public void onStart() {
        super.onStart();
        btAdapter = BluetoothAdapter.getDefaultAdapter();

        if(btAdapter != null){
            if(btAdapter.isEnabled()){
                targetDevice = BluetoothUtils.findPairedDevice(Settings.TARGET_BT_DEVICE_NAME, btAdapter);

                if(targetDevice != null){
                    ((TextView) findViewById(R.id.btFoundFlagLabel)).setText("Device Found: " + targetDevice.getName());
                    connectToTargetBtDevice();
                }
            } else {
                startActivityForResult(new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE), Settings.ENABLE_BT_REQUEST);
            }
        } else {
            Toast toast = Toast.makeText(this, "Bluetooth not supported, sorry", Toast.LENGTH_LONG);
            toast.setGravity(Gravity.BOTTOM|Gravity.CENTER, 0, 0);
            toast.show();
        }
    }

    //When the activity is destroyed, also the connection thread is destroyed.
    @Override
    public void onDestroy() {
        super.onDestroy();
        BluetoothConnectionManager.getInstance().cancel();
    }

    //Method that create the connection task, and launch it
    private void connectToTargetBtDevice(){
        UUID uuid = UUID.fromString(Settings.TARGET_BT_DEVICE_UUID);
        BluetoothConnectionTask task = new BluetoothConnectionTask(this, targetDevice, uuid);
        task.execute();
    }

    public static MainActivityHandler getHandler(){
        return uiHandler;
    }

    //Handler for the main activity
    public static class MainActivityHandler extends Handler {
        private final WeakReference<MainActivity> context;

        MainActivityHandler(MainActivity context){
            this.context = new WeakReference<>(context);
        }

        //When this handler is notified means that it's arrived the welcome message,
        //so the only thing to do is change activity
        public void handleMessage(Message msg) {
            Intent i = new Intent(context.get(), LoggingActivity.class);
            context.get().startActivityForResult(i, Settings.REQUEST_OPEN_DOOR);
        }
    }
}
