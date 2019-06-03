package embedded.smartdoor;

import android.bluetooth.BluetoothSocket;
import android.os.Message;
import android.util.Log;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.Objects;
import java.util.Set;

//Public thread for the connections, this thread is responsible of receiving and sending messages through the channel
public class BluetoothConnectionManager extends Thread {

    private BluetoothSocket btSocket;
    private InputStream btInStream;
    private OutputStream btOutStream;

    private volatile boolean stop = true;

    //Pattern Singleton
    private static BluetoothConnectionManager instance = new BluetoothConnectionManager();

    public static BluetoothConnectionManager getInstance() {
        return instance;
    }

    public void setChannel(BluetoothSocket socket) {
        btSocket = socket;

        try {
            btInStream = socket.getInputStream();
            btOutStream = socket.getOutputStream();
        } catch (IOException e) {
            e.printStackTrace();
        }

        stop = false;
    }

    //Always try to read from the channel.
    //When there is an information, it is read till the newline character
    public void run() {
        while (!stop) {
            try {
                StringBuffer buf = new StringBuffer("");

                int data = btInStream.read();
                while ((char)data != '\n') {
                    buf.append((char) data);
                    data = btInStream.read();
                }

                dispatchMsg(buf.toString().trim());
            } catch (Exception e) {
                System.err.println(e.toString());
            }
        }
    }

    public boolean sendMsg(String msg) {
        if (btOutStream == null)
            return false;

        char[] array = msg.toCharArray();
        byte[] bytes = new byte[array.length];

        for (int i = 0; i < array.length; i++) {
            bytes[i] = (byte) array[i];
        }

        try {
            btOutStream.write(bytes);
            btOutStream.flush();
        } catch (IOException ex) {
            ex.printStackTrace();
            return false;
        }

        return true;
    }

    public void cancel() {
        try {
            btSocket.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    //Method to dispatch messages through activities.
    //Due to the fact that in my application there are only 3 activities and the messages between
    //all components are pre-known, this method does a simply check to discover which activity should be notified.
    private void dispatchMsg(String msg){
        Message m = new Message();
        m.obj = msg;
        if(Objects.equals(msg, Settings.WELCOME)) MainActivity.getHandler().sendMessage(m);
        else if(Objects.equals(msg.substring(0,1), "T") || Objects.equals(msg, Settings.LOGOUT)) SystemActivity.getHandler().sendMessage(m);
        else LoggingActivity.getHandler().sendMessage(m);
    }

}
