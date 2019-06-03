package embedded.smartdoor;

import android.bluetooth.BluetoothDevice;
import android.bluetooth.BluetoothSocket;
import android.os.AsyncTask;
import android.widget.TextView;

import java.io.IOException;
import java.util.UUID;

//Public Async Task to connect to the target device
public class BluetoothConnectionTask extends AsyncTask<Void, Void, Boolean> {

    private BluetoothSocket btSocket = null ;
    private MainActivity context = null;

    public BluetoothConnectionTask(MainActivity context, BluetoothDevice server, UUID uuid){

        this.context = context;

        try {
            btSocket = server.createRfcommSocketToServiceRecord(uuid);
        } catch ( IOException e){
            e.printStackTrace();
        }
    }

    //In background this task must connect to the device through a socket.
    @Override
    protected Boolean doInBackground (Void ... params ){
        try{
            btSocket.connect();
        } catch(IOException connectException){
            try{
                btSocket.close();
            } catch(IOException closeException){
                closeException.printStackTrace();
            }

            connectException.printStackTrace();
            return false;
        }
        //Create the connection manager and set the channel to the socket created.
        BluetoothConnectionManager cm = BluetoothConnectionManager.getInstance ();
        cm.setChannel(btSocket);
        cm.start();

        return true;
    }

    //When it finishes, the label is set to the status of the connection
    @Override
    protected void onPostExecute(Boolean connected) {
        TextView flagLabel = context.findViewById(R.id.btStateFlag);

        if(connected) {
            flagLabel.setText("Status: Connected");
        } else {
            flagLabel.setText("Status: Not Connected");
        }
    }
}
