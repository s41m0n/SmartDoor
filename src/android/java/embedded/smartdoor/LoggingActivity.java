package embedded.smartdoor;

import android.content.Intent;
import android.os.Handler;
import android.os.Message;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import org.w3c.dom.Text;

import java.lang.ref.WeakReference;

public class LoggingActivity extends AppCompatActivity {

    private Button login;

    private static LoggingActivityHandler uiHandler;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_logging);

        initUI();
        uiHandler = new LoggingActivityHandler(this);
    }

    private void initUI() {
        login = findViewById(R.id.login);
        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                EditText username = findViewById(R.id.username);
                EditText password = findViewById(R.id.password);
                BluetoothConnectionManager.getInstance().sendMsg(username.getText() + " " + password.getText());
            }
        });
    }

    @Override
    public void onBackPressed() {
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        login.performClick();
        BluetoothConnectionManager.getInstance().cancel();
    }

    public static LoggingActivityHandler getHandler(){
        return uiHandler;
    }

    public static class LoggingActivityHandler extends Handler {
        private final WeakReference<LoggingActivity> context;

        LoggingActivityHandler(LoggingActivity context){
            this.context = new WeakReference<>(context);
        }

        public void handleMessage(Message msg) {

            Object obj = msg.obj;

            if(obj instanceof String){
                String message = obj.toString();
                switch (message) {
                    case Settings.LOGIN_KO: {
                        Toast toast = Toast.makeText(context.get(), "Invalid password", Toast.LENGTH_LONG);
                        toast.setGravity(Gravity.BOTTOM|Gravity.CENTER, 0, 0);
                        toast.show();
                        break;
                    }
                    case Settings.NEED_USER: {
                        Toast toast = Toast.makeText(context.get(), "User doesn't exist", Toast.LENGTH_LONG);
                        toast.setGravity(Gravity.BOTTOM|Gravity.CENTER, 0, 0);
                        toast.show();
                        break;
                    }
                    case Settings.LOGIN_OK: {
                        Toast toast = Toast.makeText(context.get(), "Login Ok! Enter in 2 seconds", Toast.LENGTH_SHORT);
                        toast.setGravity(Gravity.BOTTOM|Gravity.CENTER, 0, 0);
                        toast.show();
                        break;
                    }
                    case Settings.NEED_CONFIRM: {
                        Toast toast = Toast.makeText(context.get(), "User not confirmed", Toast.LENGTH_LONG);
                        toast.setGravity(Gravity.BOTTOM|Gravity.CENTER, 0, 0);
                        toast.show();
                        break;
                    }
                    case Settings.INSIDE: {
                        Intent i = new Intent(context.get(), SystemActivity.class);
                        context.get().startActivityForResult(i, Settings.LOGGED);
                        break;
                    }
                    case Settings.FAILED: {
                        Toast toast = Toast.makeText(context.get(), "No one has been detected", Toast.LENGTH_LONG);
                        toast.setGravity(Gravity.BOTTOM|Gravity.CENTER, 0, 0);
                        toast.show();
                        break;
                    }
                    case Settings.STAY: {
                        Toast toast = Toast.makeText(context.get(), "Turn in front the door please", Toast.LENGTH_LONG);
                        toast.setGravity(Gravity.BOTTOM|Gravity.CENTER, 0, 0);
                        toast.show();
                        break;
                    }
                }
            }
        }
    }
}
