package embedded.smartdoor;

import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.os.Handler;
import android.os.Message;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.Toast;

import org.w3c.dom.Text;

import java.lang.ref.WeakReference;
import java.util.Objects;

//The activity running while the user is inside the door (already logged)
public class SystemActivity extends AppCompatActivity {

    private static SystemActivityHandler uiHandler; //Activity Handler
    private SeekBar intensity; //The bar for the intensity
    private TextView percentage; //The TextView with the percentage of the intensity
    private Button logout; //The logout button

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_system);
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);

        initUI();
        uiHandler = new SystemActivityHandler(this);
    }

    private void initUI() {
        intensity = findViewById(R.id.intensity);
        percentage = findViewById(R.id.intensityPercentage);
        logout = findViewById(R.id.logout);

        //When logout is pressed -> close the app
        logout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finishAffinity();
            }
        });

        intensity.setOnSeekBarChangeListener(new SeekBar.OnSeekBarChangeListener() {
            int progressValue;
            //Whenever a user change the value of the seek bar, it is saved
            @Override
            public void onProgressChanged(SeekBar seekBar, int i, boolean b) {
                progressValue = i;
                percentage.setText("" + progressValue + " %");
            }

            @Override
            public void onStartTrackingTouch(SeekBar seekBar) {

            }

            //When the user release the seek bar, the value is sent to the Bluetooth Device connected (Arduino)
            //It is send VALUE + 1, because i used '0' as a signal of stop communicating
            @Override
            public void onStopTrackingTouch(SeekBar seekBar) {
                String msg = "" + (progressValue+1);
                BluetoothConnectionManager.getInstance().sendMsg(msg.trim());
            }
        });
    }

    //Method to disable back key
    @Override
    public void onBackPressed() {
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        BluetoothConnectionManager.getInstance().cancel();
    }

    //Public method to get the handler of this activity
    public static SystemActivityHandler getHandler(){
        return uiHandler;
    }

    //Handler class for this activity, it contains a weak reference thanks to when the handler is notified by a message,
    //he can change and work on this activity. (Used with Thread)
    public static class SystemActivityHandler extends Handler {
        private final WeakReference<SystemActivity> context;

        SystemActivityHandler(SystemActivity context){
            this.context = new WeakReference<>(context);
        }

        public void handleMessage(Message msg) {

            Object obj = msg.obj;

            if(obj instanceof String){
                String message = obj.toString();

                //This handler is notified when there is a new Temperature message or when the btnExit (Arduino) is clicked
                if(Objects.equals(message.substring(0,1), "T")) {
                    message = message.substring(1, obj.toString().length());
                    ((TextView)context.get().findViewById(R.id.temperature)).setText(message + " °C");
                } else {
                    context.get().finishAffinity();
                }
            }
        }
    }
}
